<?php
namespace HalimonAlexander\HtmlSnippets;

class Pagination
{
    const MIN_BUTTONS_COUNT = 3;
    
    protected $hrefFormat  = "?page=%d";
    protected $titleFormat = "view page %d";
    protected $dotsFormat  = '<span>.&nbsp;.&nbsp;.</span>';
    protected $enabledButtonFormat  = '<a class="%s" href="%s" title="%s">%s</a>';
    protected $disabledButtonFormat = '<span class="%s" title="%s">%s</span>';
    protected $paginatorFormat      = '<p class="%s">%s</p>';
    
    protected $controlButtonsCssStyle = [
        "first"    => "left",
        "previous" => "",
        "next"     => "",
        "last"     => "right",
    ];

    protected $controlButtonsText = [
        "first"    => "<<",
        "previous" => "<",
        "next"     => ">",
        "last"     => ">>",
    ];
    
    protected $displayAllButtons = false;
    protected $displayButtonsCount = 4;
    
    private $currentPage;
    private $totalPages;
    private $itemsPerPage;

    public function __construct(int $itemsCount, int $itemsPerPage)
    {
        $this->currentPage  = 1;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalPages   = $itemsCount ? ceil($itemsCount / $itemsPerPage) : 1;
    }
    
    public function fetch($cssClass = 'paginator'): string
    {
        $buttons = $this->getButtons();
        
        return sprintf(
            $this->paginatorFormat,
            $cssClass ?? '',
            join("", $buttons)
        );
    }
    
    public function setDisplayButtonsCount(int $value)
    {
        if ($value < $this::MIN_BUTTONS_COUNT)
            $value = $this::MIN_BUTTONS_COUNT;
        
        $this->displayButtonsCount = $value;
    }
    
    public function setLinkFormat(string $format)
    {
        $this->hrefFormat = $format;
    }
    
    public function setCurrentPage(int $pageNumber)
    {
        if ($pageNumber < 1)
            $pageNumber = 1;
        
        if ($pageNumber > $this->totalPages)
            $pageNumber = $this->totalPages;
        
        $this->currentPage = $pageNumber;
    }
    
    private function getButtons(): array
    {
        if ($this->totalPages == 0)
            return [];
        
        $buttons = [];
        
        $buttons[] = $this->createButton("first");
        $buttons[] = $this->createButton("previous");
    
        $dotsAdded = false;
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($this->canCreateButton($i)) {
                $buttons[] = $this->createButton($i);
                $dotsAdded = false;
            } elseif (!$dotsAdded) {
                $buttons[] = $this->dotsFormat;
                $dotsAdded = true;
            }
        }
            
        $buttons[] = $this->createButton("next");
        $buttons[] = $this->createButton("last");
        
        return $buttons;
    }
    
    private function canCreateButton(int $i)
    {
        if ($this->displayAllButtons)
            return true;
        
        if ($i <= $this->displayButtonsCount || $i >= $this->totalPages - $this->displayButtonsCount)
            // left and right sides
            return true;
        
        $leftBound  = $this->currentPage - ceil($this->displayButtonsCount / 2);
        $rightBound = $this->currentPage + ceil($this->displayButtonsCount / 2);
        
        if ($i >= $leftBound && $i <= $rightBound)
            return true;
        
        return false;
    }
    
    private function createButton($pageNumber)
    {
        if ($pageNumber == "first")
            return $this->currentPage == 1 
                ? $this->getDisabledButton(1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber])
                :  $this->getEnabledButton(1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber]);
        elseif ($pageNumber == "previous")
            return $this->currentPage == 1 
                ? $this->getDisabledButton($this->currentPage - 1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber])
                :  $this->getEnabledButton($this->currentPage - 1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber]);
        elseif ($pageNumber == "next")
            return $this->currentPage == $this->totalPages 
                ? $this->getDisabledButton($this->currentPage + 1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber])
                :  $this->getEnabledButton($this->currentPage + 1, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber]);
        elseif ($pageNumber == "last")
            return $this->currentPage == $this->totalPages 
                ? $this->getDisabledButton($this->totalPages, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber])
                :  $this->getEnabledButton($this->totalPages, $this->controlButtonsText[$pageNumber], $this->controlButtonsCssStyle[$pageNumber]);
        else
            return $this->currentPage == $pageNumber 
                ? $this->getDisabledButton($pageNumber) 
                :  $this->getEnabledButton($pageNumber);
    }
    
    private function getDisabledButton(int $pageNumber, string $buttonText = null, $cssClass = null): string
    {
        return sprintf(
            $this->disabledButtonFormat,
            $cssClass ?? '',
            $this->getButtonTitle($pageNumber),
            $buttonText ?? $pageNumber
        );
    }
    
    private function getEnabledButton(int $pageNumber, string $buttonText = null, string $cssClass = null): string
    {
        return sprintf(
            $this->enabledButtonFormat,
            $cssClass ?? '',
            $this->getButtonHref($pageNumber),
            $this->getButtonTitle($pageNumber),
            $buttonText ?? $pageNumber
        );
    }
    
    private function getButtonHref(int $pageNumber): string
    {
        return sprintf($this->hrefFormat, $pageNumber);
    }
    
    private function getButtonTitle(int $pageNumber): string
    {
        return sprintf($this->titleFormat, $pageNumber);
    }
}
