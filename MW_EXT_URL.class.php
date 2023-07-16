<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use OutputPage, Parser, Skin;

/**
 * Class MW_EXT_URL
 */
class MW_EXT_URL
{
  /**
   * * Clear URL.
   *
   * @param $string
   *
   * @return string
   */
  private static function clearURL($string): string
  {
    return rawurlencode(trim($string));
  }

  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setFunctionHook('url', [__CLASS__, 'onRenderTag']);
  }

  /**
   * Render `URL` function.
   *
   * @param Parser $parser
   * @param string $type
   * @param string $content
   * @param string $title
   *
   * @return string|null
   */
  public static function onRenderTag(Parser $parser, string $type = '', string $content = '', string $title = ''): ?string
  {
    // Argument: type.
    $getType = MW_EXT_Kernel::outClear($type ?? '' ?: '');

    // Argument: content.
    $getContent = MW_EXT_Kernel::outClear($content ?? '' ?: '');

    // Argument: title.
    $getTitle = MW_EXT_Kernel::outClear($title ?? '' ?: '');

    // Build URL.
    switch ($getType) {
      case 'address':
        $outContent = $getContent;
        $outTitle = $getTitle ? $getTitle : $outContent;
        $outScheme = 'https://';
        $outIcon = 'fas fa-map-marker-alt';
        $outClass = 'address';
        $outURL = 'google.ru/maps/place/' . self::clearURL($outContent);
        break;
      case 'email':
        $outContent = $getContent;
        $outTitle = $getTitle ? $getTitle : $getContent;
        $outScheme = 'mailto:';
        $outIcon = 'fas fa-envelope';
        $outClass = 'email';
        $outURL = $outContent;
        break;
      case 'tel':
        $outContent = preg_replace('#[^0-9\+]#', '', $getContent);
        $outTitle = $getTitle ? $getTitle : $getContent;
        $outScheme = 'tel:';
        $outIcon = 'fas fa-phone';
        $outClass = 'tel';
        $outURL = $outContent;
        break;
      case 'fax':
        $outContent = preg_replace('#[^0-9\+]#', '', $getContent);
        $outTitle = $getTitle ? $getTitle : $getContent;
        $outScheme = 'fax:';
        $outIcon = 'fas fa-fax';
        $outClass = 'fax';
        $outURL = $outContent;
        break;
      case 'whatsapp':
        $outContent = preg_replace('#[^0-9]#', '', $getContent);
        $outTitle = $getTitle ? $getTitle : $outContent;
        $outScheme = 'https://';
        $outIcon = 'fab fa-whatsapp';
        $outClass = 'whatsapp';
        $outURL = 'api.whatsapp.com/send?phone=' . $outContent;
        break;
      case 'tg':
        $outContent = $getContent;
        $outTitle = $getTitle ? $getTitle : $outContent;
        $outScheme = 'tg://';
        $outIcon = 'fab fa-telegram';
        $outClass = 'tg';
        $outURL = 'resolve?domain=' . $outContent;
        break;
      case 'viber':
        $outContent = $getContent;
        $outTitle = $getTitle ? $getTitle : $outContent;
        $outScheme = 'viber://';
        $outIcon = 'fab fa-viber';
        $outClass = 'viber';
        $outURL = 'public?id=' . $outContent;
        break;
      case 'skype':
        $outContent = $getContent;
        $outTitle = $getTitle ? $getTitle : $outContent;
        $outScheme = 'skype:';
        $outIcon = 'fab fa-skype';
        $outClass = 'skype';
        $outURL = $outContent;
        break;
      default:
        $parser->addTrackingCategory('mw-url-error-category');

        return null;
    }

    // Out HTML.
    $outHTML = '<a class="mw-url mw-url-' . $outClass . '" href="' . $outScheme . $outURL . '" target="_blank" rel="nofollow">';
    $outHTML .= '<i class="fa-icon ' . $outIcon . '"></i>';
    $outHTML .= '<span>' . $outTitle . '</span>';
    $outHTML .= '</a>';

    // Out parser.
    return $parser->insertStripItem($outHTML, $parser->getStripState());
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.url.styles']);
  }
}
