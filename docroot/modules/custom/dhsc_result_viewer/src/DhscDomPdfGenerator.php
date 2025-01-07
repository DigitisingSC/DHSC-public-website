<?php

namespace Drupal\dhsc_result_viewer;

use Dompdf\Dompdf;
use Drupal\pdf_generator\DomPdfGenerator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines helpers methods to help in managing config which used in SCity.
 */
class DhscDomPdfGenerator extends DomPdfGenerator {

  /**
   * @inheritDoc
   */
  public function getResponse($title, array $content, $preview = FALSE, array $options = [], $pageSize = 'A4', $showPagination = 0, $paginationX = 0, $paginationY = 0, $disposition = 'portrait', $cssText = NULL, $cssPath = NULL, $forceDownload = TRUE) {
    $request = $this->requestStack->getCurrentRequest();
    $base_url = $request->getSchemeAndHttpHost();

    // By default we load some options.
    // The user can override these options.
    foreach ($options as $key => $option) {
      $this->options->set($key, $option);
    }

    // Dompdf needs to be initialized with custom options if they are supplied.
    $this->dompdf = new Dompdf($this->options);

    $css = file_get_contents(\Drupal::service('extension.list.module')->getPath('pdf_generator') . '/css/pdf.css');

    // Add inline css from text.
    if (!empty($cssText)) {
      $css .= "\r\n";
      $css .= $cssText;
    }
    // Add inline css from file.
    if (!empty($cssPath) && file_exists($cssPath)) {
      $css .= "\r\n";
      $css .= file_get_contents($cssPath);
    }

    $build = [
      '#theme' => 'pdf_generator_print',
      '#css' => '',
      '#content' => $content,
      '#title' => $title,
    ];

    if ($preview) {
      return $build;
    }
    $html = '<style>' . str_ireplace("BASE_URL", $base_url, $css) . '</style>' . $this->renderer->render($build);

    if (function_exists('utf8_decode')) {
      $html = utf8_decode($html);
    }
    elseif (function_exists('mb_convert_encoding')) {
      $html = mb_convert_encoding($html, 'ISO-8859-1', 'UTF-8');
    }
    $html = htmlspecialchars_decode(htmlentities($html, ENT_NOQUOTES, 'ISO-8859-1'), ENT_NOQUOTES);

    // $html = str_replace('src="' . $base_url . '/', 'src="/', $html);
    //    $html = str_replace('href="/', 'href="' . $base_url . '/', $html);.
    $html = str_replace('src="/', 'src="' . $base_url . '/', $html);
    $html = str_replace('&nbsp;', ' ', $html);

    // Echo $base_url;
    //    echo $html;
    //    exit();
    $this->dompdf->setOptions($this->options);
    $this->dompdf->loadHtml($html);
    $this->dompdf->setPaper($pageSize, $disposition);
    $this->dompdf->render();
    $response = new Response();
    $response->setContent($this->dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    if (is_array($title)) {
      $title = $this->renderer->render($title);
    }
    $response->headers->set('Content-Disposition', "attachment; filename={$title}.pdf");
    return $response;
  }

}
