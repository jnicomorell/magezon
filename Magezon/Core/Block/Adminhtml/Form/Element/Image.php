<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Form\Element;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Custom image element for form fields in adminhtml
 */
class Image extends AbstractElement
{
    /**
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Constructor
     *
     * @param Factory $factoryElement
     * @param Escaper $escaper
     * @param UrlInterface $backendUrl
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        Escaper $escaper,
        UrlInterface $backendUrl,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        // Do not pass the deprecated CollectionFactory to the parent
        parent::__construct($factoryElement, null, $escaper, $data);

        $this->setType('text');
        $this->setExtType('text');
        $this->_backendUrl = $backendUrl;
        $this->_storeManager = $storeManager;
    }

    /**
     * Get the HTML output for the image element
     *
     * @return string
     */
    public function getElementHtml(): string
    {
        $html = '';
        $htmlId = $this->getHtmlId();

        $html .= '<div class="mgz-form_image">';

        $beforeElementHtml = $this->getBeforeElementHtml();
        if ($beforeElementHtml) {
            $html .= '<label class="addbefore" for="' . $htmlId . '">' . $beforeElementHtml . '</label>';
        }

        $html .= '<input id="' . $htmlId . '" name="' . $this->getName() . '" ' . $this->_getUiId() . ' value="' .
            $this->getEscapedValue() . '" ' . $this->serialize($this->getHtmlAttributes()) . '/>';

        $visible = !$this->getDisabled();

        $html .= $this->_getButtonHtml([
            'title' => __('Insert Image'),
            'onclick' => "MgzMediabrowserUtility.openDialog('" . $this->_backendUrl->getUrl('mgzcore/wysiwyg_images/index', ['target_element_id'=> $this->getName()]) . "', false, false, 'Insert Image', {closed: function() { jQuery('#mceModalBlocker').show()}})",
            'class' => 'action-add-image plugin',
            'style' => $visible ? '' : 'display:none',
        ]);

        $afterElementJs = $this->getAfterElementJs();
        if ($afterElementJs) {
            $html .= $afterElementJs;
        }

        $afterElementHtml = $this->getAfterElementHtml();
        if ($afterElementHtml) {
            $html .= '<label class="addafter" for="' . $htmlId . '">' . $afterElementHtml . '</label>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Generate the custom button HTML
     *
     * @param array $data
     * @return string
     */
    protected function _getButtonHtml(array $data): string
    {
        $htmlId = $this->getHtmlId();
        $html = '<button type="button"';
        $html .= ' class="scalable ' . ($data['class'] ?? '') . '"';
        $html .= isset($data['onclick']) ? ' onclick="' . $data['onclick'] . '"' : '';
        $html .= isset($data['style']) ? ' style="' . $data['style'] . '"' : '';
        $html .= isset($data['id']) ? ' id="' . $data['id'] . '"' : '';
        $html .= '>';
        $html .= isset($data['title']) ? '<span><span><span>' . $data['title'] . '</span></span></span>' : '';
        $html .= '</button>';

        $imgSrc = $this->getValue();
        if ($imgSrc && !preg_match("/^http\:\/\/|https\:\/\//", $imgSrc)) {
            $imgSrc = $this->getMediaUrl() . $imgSrc;
        }

        $html .= '<div id="' . $htmlId . '-preview" class="image-preview" ' . ($imgSrc ? 'style="display: block"' : '') .'><a href="' . $imgSrc . '" id="' . $htmlId . '-preview_image"><img  src="' . $imgSrc . '"/></a></div>';

        $html .= '<script>
            require(["jquery", "Magezon_Core/js/mage/browser"], function($) {
                $("#' . $htmlId . '-preview_image").click(function(e) {
                    var win = window.open("", "preview", "width=500,height=500,resizable=1,scrollbars=1");
                    win.document.open();
                    win.document.write("<body style=\"padding:0;margin:0\"><img src=\'"+$(this).find(\"img\").eq(0).attr(\"src\")+"\' id=\"image_preview\"/></body>");
                    win.document.close();
                    win.onload = function(){
                        var img = win.document.getElementById("image_preview");
                        win.resizeTo(img.width+40, img.height+80);
                    };
                    return false;
                });
                $(document).on("keyup, change", "#' . $htmlId . '", function() {
                    var val = $(this).val();
                    if (val) {
                        $("#' . $htmlId . '-preview").show();
                        if (((val.indexOf("wysiwyg") !== -1) && (val.indexOf("http") === -1)) || ((val.indexOf("wysiwyg") === -1) && (val.indexOf("http") === -1))) {
                            $("#' . $htmlId . '-preview img").attr("src", "' . $this->getMediaUrl() . '" + val);
                            $("#' . $htmlId . '").attr("data-link", "' . $this->getMediaUrl() . '" + val);
                        } else {
                            $("#' . $htmlId . '-preview img").attr("src", val);
                            $("#' . $htmlId . '").attr("data-link", val);
                        }
                    } else {
                        $("#' . $htmlId . '-preview").hide();
                    }
                }).change();
            });
        </script>';

        return $html;
    }

    /**
     * Retrieve media base URL
     *
     * @return string
     */
    public function getMediaUrl(): string
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
