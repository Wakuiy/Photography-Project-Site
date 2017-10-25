<?php

namespace Drupal\dui_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Access\AccessResult;
use Drupal\Component\Utility\Crypt;

/**
 * An DuiViewController.
 */
class DuiViewController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function duiViewListModules($hmac) {

    // @todo move this to Access callback.
    // Get the key variants from the config of the site.
    $publicKey = \Drupal::config('dui_view.settings')->get('dui_view_site_key', 0);
    $privateKey = \Drupal::config('dui_view.settings')->get('dui_view_site_key_priv', 0);

    // Check key validity.
    if ($hmac != Crypt::hmacBase64($publicKey, $privateKey)) {
      throw new AccessDeniedHttpException();
    }

    // Get the current module data.
    $moduleData = system_rebuild_module_data();

    $modules = array();

    foreach ($moduleData as $name => $data) {

      // Conditional block to remove any hidden, multi-project, core or
      // inactive modules.
      if (isset($data->info['hidden']) && $data->info['hidden'] == TRUE) {
        continue;
      }
      if (isset($data->info['project']) && $data->info['project'] != $data->name) {
        continue;
      }
      if (isset($data->info['package']) && $data->info['package'] == 'Core') {
        continue;
      }

      // Each module that passes the conditionals are stored in an array.
      $modules[$name] = array(
        'name' => $data->info['name'],
        'version' => $data->info['version'],
        'status' => $data->status,
      );
    }

    // Global variables and module array stored within a single array.
    $site = array(
      'sitename' => \Drupal::config('system.site')->get('name'),
      'core' => \Drupal::VERSION,
      'modules' => $modules,
      'data' => array(),
    );

    // Get some variables and pass them along with the 'data' array.
    $site['data']['preprocess_css'] = \Drupal::config('system.performance')->get('css.preprocess', 0);
    $site['data']['preprocess_js'] = \Drupal::config('system.performance')->get('js.preprocess', 0);
    $site['data']['page_compression'] = \Drupal::config('system.performance')->get('js.gzip', 0);
    $site['data']['cache'] = \Drupal::config('system.performance')->get('cache.page.max_age', 0);
    $site['data']['cron_last'] = \Drupal::state()->get('system.cron_last', 0);
    $site['data']['error_level'] = \Drupal::config('system.logging')->get('error_level', 0);

    /*
     * Also include
     * - user accounts
     * - user sessions
     * - node count
     * - comment count
     * - stats on files (temp count and size, perm count and size)
     * - number of watchdog warnings of each type
     */

    \Drupal::logger('dui_view')->info('Site data generated<br><pre>@sitedata</pre>', array('@sitedata' => print_r($site, TRUE)));

    // Outputs the filtered data as JSON string to allow pairing with the
    // controller module.
    $json = json_encode($site);

    // We encrypt the JSON before sending it out.
    $output = $this->duiViewEncrypt($json, \Drupal::config('dui_view.settings')->get('dui_view_site_key_priv', ''));

    return new JsonResponse($output);
  }

  public function access($hmac = true) {
    // @todo Check provided param.
    return AccessResult::allowedIf($hmac);
  }

  /**
   * Given plain data, encrypt it.
   *
   * @param string $data
   *   The plain text data.
   * @param string $key
   *   The private site key.
   *
   * @return string
   *   The encrypted data.
   */
  private function duiViewEncrypt($data, $key) {

    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($data, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = TRUE);

    return base64_encode($iv . $hmac . $ciphertext_raw);
  }

  /**
   * Given encrypted data, decrypt it.
   *
   * @param string $data
   *   The encrypted data.
   * @param string $key
   *   The private site key.
   *
   * @return string
   *   The decrypted data.
   */
  public function duiViewDecrypt($data, $key) {

    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = TRUE);
    // PHP 5.6+ timing attack safe comparison.
    return hash_equals($hmac, $calcmac) ? $original_plaintext : FALSE;
  }

}
