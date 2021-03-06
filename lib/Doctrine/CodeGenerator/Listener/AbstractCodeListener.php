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

use Doctrine\Common\EventSubscriber;
use Doctrine\CodeGenerator\Builder\CodeBuilder;
use Doctrine\CodeGenerator\MetadataContainer;
use Doctrine\CodeGenerator\GenerationProject;

abstract class AbstractCodeListener implements EventSubscriber
{
    protected $code;
    protected $metadata;
    protected $project;

    public function setCodeBuilder(CodeBuilder $builder)
    {
        $this->code = $builder;
    }

    public function setMetadataContainer(MetadataContainer $container)
    {
        $this->metadata = $container;
    }

    public function setProject(GenerationProject $project)
    {
        $this->project = $project;
    }

    public function getSubscribedEvents()
    {
        $methods = get_class_methods($this);
        $eventMethods = array();
        foreach ($methods as $method) {
            if (substr($method, 0, 2) === "on") {
                $eventMethods[] = $method;
            }
        }

        return $eventMethods;
    }
}

