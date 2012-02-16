<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\CodeGenerator\Listener;

use Doctrine\CodeGenerator\GeneratorEvent;
use Doctrine\CodeGenerator\Builder\ClassBuilder;

/**
 * Each property is turned to protected and getters/setters are added.
 */
class GetterSetterListener extends AbstractCodeListener
{
    public function onGenerateProperty(GeneratorEvent $event)
    {
        $node = $event->getNode();
        $node->type = \PHPParser_Node_Stmt_Class::MODIFIER_PROTECTED; // set protected

        $class = $event->getParent($node);
        $builder = new ClassBuilder($class);
        $code = $this->code;

        foreach ($node->props as $property) {
            if ($builder->hasMethod('set' . $property->name) || $builder->hasMethod('get' . $property->name)) {
                continue;
            }

            $builder
                ->appendMethod('set' . ucfirst($property->name))
                    ->addParam($property->name)
                    ->append($code->assignment($code->instanceVariable($property->name), $code->variable($property->name)))
                ->end()
                ->appendMethod('get' . ucfirst($property->name))
                    ->append($code->returnStmt($code->instanceVariable($property->name)))
                ->end();

            $this->metadata->setAttribute($builder->getMethod('set' . ucfirst($property->name)), 'property', $property);
            $this->metadata->setAttribute($builder->getMethod('get' . ucfirst($property->name)), 'property', $property);
        }
    }

    public function getSubscribedEvents()
    {
        return array('onGenerateProperty');
    }
}

