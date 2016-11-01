page {
  includeCSS {
    tkmedia = EXT:tkmedia/Resources/Public/Styles/tkmedia.css
  }

  includeJSFooter {
    tkmedia-image = EXT:tkmedia/Resources/Public/JavaScript/responsiveimages.js
    tkmedia-plyr = EXT:tkmedia/Resources/Public/JavaScript/plyr/plyr.js
    tkmedia = EXT:tkmedia/Resources/Public/JavaScript/tkmedia.js
  }
}

lib.fluidContent {
  layoutRootPaths {
    99 = EXT:tkmedia/Resources/Private/Layouts/Fluid_Styled_Content/
  }

  templateRootPaths {
    99 = EXT:tkmedia/Resources/Private/Templates/Fluid_Styled_Content/
  }

  partialRootPaths {
    99 = EXT:tkmedia/Resources/Private/Partials/Fluid_Styled_Content/
  }



  settings {
    media {
      popup {
        #crop.data = file:current:crop
        crop.data >
        linkParams {
          ATagParams.dataWrap = class="{$styles.content.textmedia.linkWrap.lightboxCssClass}" data-lightbox="{$styles.content.textmedia.linkWrap.lightboxDataAttribute}"
        }
      }
    }
  }
}

#ImageWidths
plugin.tkmedia {
  settings {
    //default, srcset, data
    layoutKey = data
    defaultSrc = typo3conf/ext/tkmedia/Resources/Public/Images/blank.png
    allowUpscaling = 0

    sourceCollection {
      src {
        srcset = 1x
      }

      src-hidpi {
        pixelDensity = 2
        srcset = 2x
      }

      #      small {
      #        width = 480
      #      }

      #      medium {
      #        width = 960
      #      }

      #      high {
      #        width = 1920
      #      }
    }
  }
}

tt_content {
  textmedia {
    settings {

    }

    dataProcessing {
        20 = ThomasK\Tkmedia\DataProcessing\GalleryProcessor
        20 {
          maxGalleryWidth = {$styles.content.textmedia.maxW}
          maxGalleryWidthInText = {$styles.content.textmedia.maxWInText}
          columnSpacing = {$styles.content.textmedia.columnSpacing}
          borderWidth = {$styles.content.textmedia.borderWidth}
          borderPadding = {$styles.content.textmedia.borderPadding}
        }

      //Poster Image
      110 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      110 {
        references.fieldName = tkmedia_poster
        as = poster
      }

      //Assets
      120 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      120 {
        references.fieldName = assets
        as = assets
      }
    }
  }
}
