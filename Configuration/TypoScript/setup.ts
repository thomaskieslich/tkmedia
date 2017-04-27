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
    templateRootPaths {
        110 = EXT:tkmedia/Resources/Private/Templates/
    }

    partialRootPaths {
        110 = EXT:tkmedia/Resources/Private/Partials/
    }

    layoutRootPaths {
        110 = EXT:tkmedia/Resources/Private/Layouts/
    }

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
