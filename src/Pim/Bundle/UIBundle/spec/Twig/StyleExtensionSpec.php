<?php

namespace spec\Pim\Bundle\UIBundle\Twig;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StyleExtensionSpec extends ObjectBehavior
{
    function it_is_a_twig_extension()
    {
        $this->shouldHaveType('\Twig_Extension');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('pim_ui_style_extension');
    }

    function it_defines_filters()
    {
        $filters = $this->getFilters();

        $filters->shouldHaveCount(1);
        $filters[0]->shouldBeAnInstanceOf('\Twig_SimpleFilter');
        $filters[0]->getName()->shouldReturn('highlight');
    }

    function it_highlights()
    {
        $this->highlight('toto')->shouldReturn('<span class="AknRule-attribute">toto</span>');
    }
}
