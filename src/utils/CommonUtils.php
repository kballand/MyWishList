<?php

namespace MyWishList\utils;


class CommonUtils {

    public static function importScripts($scripts) {
        $scriptImport = '';
        if(is_array($scripts)) {
            foreach($scripts as $script) {
                if(is_string($script)) {
                    $scriptImport .=
<<< END
<script type="text/javascript" src="$script"></script>
END;
                }
            }
        } else if(is_string($scripts)) {
            $scriptImport =
<<< END
<script type="text/javascript" src="$scripts"></script>
END;
        }
        return $scriptImport;
    }


    public static function importCSS($cssFiles) {
        $cssImport = '';
        if(is_array($cssFiles)) {
            foreach($cssFiles as $cssFile) {
                if(is_string($cssFile)) {
                    $cssImport .=
<<< END
<link rel="stylesheet" href="$cssFile" />
END;
                }
            }
        } else if(is_string($cssFiles)) {
            $cssImport =
<<< END
<link rel="stylesheet" href="$cssFiles" />
END;
        }
        return $cssImport;
    }

}