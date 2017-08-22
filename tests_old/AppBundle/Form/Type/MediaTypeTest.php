<?php

namespace tests\AppBundle\Form\Type;

use AppBundle\Entity\Media;
use AppBundle\Form\Type\MediaType;

class MediaTypeTest extends \AppBundle\Test\TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'trop trognon ce chaton',
            'url' => 'http://exh5266.cias.rit.edu/256/homework3/images/kitten.jpg',
        ];

        $form = $this->factory->create(MediaType::class);

        $media = new Media();
        $object = $this->fromArray($media, $formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        $validationError = $form->getErrors();
        $this->assertEquals($validationError->count(), 0);
    }
}