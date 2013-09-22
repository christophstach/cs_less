.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


In eigene Erweiterung integriegen
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

LessCss stellt einen ViewHelper bereit
um direkt aus dem Fluid-Template LESS-Dateien einzubinden. Die
angegenbene LESS-Datei wird zu einer CSS-Datei kompiliert und
innerhalb des <head>-Tags in das Layout eingebunden.


Namespace referenzieren
"""""""""""""""""""""""

Am Anfang des Fluid Templates muss eine Referenz auf den Namespace
**Stach\CsLess\ViewHelpers** erstellt werden.

Beispiel:

::

	{namespace less=Stach\CsLess\ViewHelpers}


LESS-Datei einbinden
""""""""""""""""""""

Danach muss die LESS-Datei über den ViewHelper eingebunden innerhalb
der section “Resources” eingebunden werden.

Beispiel:

::

	<less:style href="typo3conf/ext/cayenne_elite_escort_page/Resources/Public/Styles/layout-content-area.less" /> 


Bespiel für ein komplettes Fluid-Template
"""""""""""""""""""""""""""""""""""""""""

::

	{namespace flux=Tx_Flux_ViewHelpers}
	{namespace less=Stach\CsLess\ViewHelpers}
	{namespace v=Tx_Vhs_ViewHelpers}

	<f:layout name="Default" />

	<f:section name="Configuration">
		<flux:flexform id="Single" label="Das ist	ein Beispiel-Layout.">

		</flux:flexform>
	</f:section>

	<f:section name="Resources">
		<less:style href="typo3conf/ext/design/Resources/Public/Styles/layout-beispiel.less" />
	</f:section>

	<f:section name="Body">
		<div id="page_wrapper">
			<div id="page">
				<div id="header_wrapper">
					<div id="header">&nbsp;</div>
				</div>
				<div id="main_wrapper">
					<div id="main">
						<div id="content_wrapper">
							<div id="content">
								<!--TYPO3SEARCH_begin-->
								<v:page.content.render column="0" />
								<!--TYPO3SEARCH_end-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</f:section>