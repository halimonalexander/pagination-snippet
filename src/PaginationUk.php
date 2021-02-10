<?php
namespace HalimonAlexander\HtmlSnippets;

class PaginationUk extends Pagination
{
    protected $dotsFormat           = '<li class="uk-disabled">.&nbsp;.&nbsp;.</li>';
    protected $enabledButtonFormat  = '<li><a class="uk-active %s" href="%s" title="%s">%s</a></li>';
    protected $disabledButtonFormat = '<li class="uk-disabled %s" title="%s">%s</li>';
    protected $paginatorFormat      = '<ul class="uk-pagination %s">%s</ul>';
    
    protected $controlButtonsCssStyle = [
        "first"    => "uk-icon-angle-double-left",
        "previous" => "uk-icon-angle-left",
        "next"     => "uk-icon-angle-right",
        "last"     => "uk-icon-angle-double-right",
    ];

    protected $controlButtonsText = [
        "first"    => "",
        "previous" => "",
        "next"     => "",
        "last"     => "",
    ];
}
