<?php

namespace Bricks;

use Bricks\Element\Button;
use Bricks\Element\Element;

class View extends Object
{
    /** @var Form */
    protected $form;

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function render()
    {
        echo '<form ' . $this->form->getAttributes() . '>';

        $elements = $this->form->getElements();
        $elementsCount = sizeof($elements);

        for($i = 0; $i < $elementsCount; ++$i) {
            /** @var Element $element */
            $element = $elements[$i];

            if($element instanceof Button) {

            } else {
                $element->render();
            }
        }

        echo '</form>';
    }
}