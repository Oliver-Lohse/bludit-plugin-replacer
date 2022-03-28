<?php

// Dieses Plugin ersetzt in einzelnen Posts Inhalte die mit {{post-slug}}
// markiert sind, mit Inhalten statischer Seiten deren Slug post-slug lautet.

class pluginReplacer extends Plugin {

	// Ersetzt den Token {{post-slug}} durch den Inhalt der statischen Seite
	// "post-slug".
	function beforeSiteLoad() {
        global $content, $pages, $items;

		foreach ($content as $key=>$page) {
            $pageContent = $page->content();

			$baustein = explode('{{', $pageContent);
			$baustein = explode('}}', $baustein[1]);

			if (strlen($baustein[0]) > 0) {
            	$pageContent = str_replace('{{'.$baustein[0].'}}', $this->getBaustein('{{'.$baustein[0].'}}'), $pageContent);
            	$page->setField('content', $pageContent);
			}
        }
    }

	// versucht einen mit {{...}} gekennzeichneten Token im Text zu finden und 
	// liefert den Inhalt aus den Klammern an den Aufrufer zurück.
	function getBaustein($baustein) {
		global $content, $pages, $items, $staticContent;
		$html ='';

		$baustein = str_replace("{{", "", $baustein);
		$baustein = str_replace("}}", "", $baustein);
		$found = FALSE;
		foreach ($staticContent as $page) {
			if ($baustein == $page->key()) {
				$found=TRUE;
				$html = '<div class="border rounded p-3">'.$page->content().'</div>';
			}
		}
		if ($found == FALSE) {
			$html = '<div class="alert alert-info">Der angegebene Baustein: '.$baustein.' konnte nicht gefunden werden, ggf. existiert kein statischer Beitrag mit diesem slug.</div>';
		}
		return $html;
	}
}