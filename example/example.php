<?php

use Bricks\Element\Button;
use Bricks\Element\Html;
use Bricks\Form;

$form = new Form('test');
$form->addElements([
    new Html('<span class="field submit">'),
    new Button('Save'),
    new Button('Reset', 'reset'),
    new Html('</span>'),
]);
$form->render();