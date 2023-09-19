<?php

namespace Drupal\dhsc_result_viewer;

use Dompdf\Dompdf;
use Drupal\Core\Render\Markup;
use Drupal\pdf_generator\DomPdfGenerator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines helpers methods to help in managing config which used in SCity.
 */
class DhscDomPdfGenerator extends DomPdfGenerator
{
  /**
   * @inheritDoc
   */
  public function getResponse($title, array $content, $preview = FALSE, array $options = [], $pageSize = 'A4', $showPagination = 0, $paginationX = 0, $paginationY = 0, $disposition = 'portrait', $cssText = NULL, $cssPath = NULL, $forceDownload = TRUE)
  {
    $request = $this->requestStack->getCurrentRequest();
    $base_url = $request->getSchemeAndHttpHost();

    // By default we load some options.
    // The user can override these options.
    foreach ($options as $key => $option) {
      $this->options->set($key, $option);
    }

    // Dompdf needs to be initialized with custom options if they are supplied.
    $this->dompdf = new Dompdf($this->options);
    $path = $this->moduleHandler->getModule('pdf_generator')->getPath();
    $css = file_get_contents($path . '/css/pdf.css');
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

    $css = str_replace("url('/", "url('" . $base_url . "/", $css);
    $build = [
      '#theme' => 'pdf_generator_print',
      '#css' => Markup::create($css),
      '#content' => $content,
      '#title' => $title,
    ];

    if ($preview) {
      return $build;
    }
    $html = (string) $this->renderer->renderRoot($build);

    $html = urldecode($html);
    $html = str_replace('src="' . $base_url . '/', 'src="/', $html);
    $html = str_replace('href="/', 'href="' . $base_url . '/', $html);
    $html = str_replace('src="/', 'src="' . DRUPAL_ROOT . '/', $html);

    $this->dompdf->setOptions($this->options);
    $this->dompdf->loadHtml($html);
    $this->dompdf->setPaper($pageSize, $disposition);

    $this->moduleHandler->alter('pdf_generator_pre_render', $this->dompdf);

    $this->dompdf->render();

    switch ($showPagination) {
      case 1:
        $canvas = $this->dompdf->getCanvas();
        $canvas->page_text($paginationX, $paginationY, "{PAGE_NUM}", NULL, 12);
        $this->dompdf->setCanvas($canvas);
        break;

      case 2:
        $canvas = $this->dompdf->getCanvas();
        $canvas->page_text($paginationX, $paginationY, "{PAGE_NUM}/{PAGE_COUNT}", NULL, 12);
        $this->dompdf->setCanvas($canvas);
        break;
    }

    $this->moduleHandler->alter('pdf_generator_post_render', $this->dompdf);

    $response = new Response();
    $response->setContent($this->dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    if (is_array($title)) {
      $title = $this->renderer->render($title);
    }
    $filename = strtolower(trim(preg_replace('#\W+#', '_', $title), '_'));
    if ($forceDownload) {
      $response->headers->set('Content-Disposition', "attachment; filename={$filename}.pdf");
    }
    return $response;
  }
}
