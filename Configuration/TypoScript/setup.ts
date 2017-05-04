page {
  includeCSS {
    tkmedia = EXT:tkmedia/Resources/Public/Styles/plyr.css
  }

  includeJSFooter {
    tkmedia-plyr = EXT:tkmedia/Resources/Public/JavaScript/plyr.js
    tkmedia = EXT:tkmedia/Resources/Public/JavaScript/tkmedia.js
  }
}

lib.contentElement {
#  templateRootPaths {
#    110 = EXT:tkmedia/Resources/Private/Templates/
#  }

  partialRootPaths {
    110 = EXT:tkmedia/Resources/Private/Partials/
  }

#  layoutRootPaths {
#    110 = EXT:tkmedia/Resources/Private/Layouts/
#  }

  settings {
    image {
      small = {$styles.content.textmedia.image.small}
      medium = {$styles.content.textmedia.image.medium}
      large = {$styles.content.textmedia.image.large}
    }

    media {
      popup {
        #                crop.data = file:current:crop
        #                crop.data >
        linkParams {
          ATagParams.dataWrap = class="{$styles.content.textmedia.linkWrap.lightboxCssClass}" data-lightbox="{$styles.content.textmedia.linkWrap.lightboxDataAttribute}"
        }
      }
    }
  }
}

lib.math = TEXT
lib.math {
  current = 1
  prioriCalc = 1
}


tt_content.textmedia {
  dataProcessing {
    10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
    10 {
      references.fieldName = assets
    }

    20 = ThomasK\Tkmedia\DataProcessing\GalleryProcessor
    20 {
      maxGalleryWidth = {$styles.content.textmedia.maxW}
      maxGalleryWidthInText = {$styles.content.textmedia.maxWInText}
      columnSpacing = {$styles.content.textmedia.columnSpacing}
      borderWidth = {$styles.content.textmedia.borderWidth}
      borderPadding = {$styles.content.textmedia.borderPadding}
    }
  }
}
