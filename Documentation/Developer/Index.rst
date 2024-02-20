.. _developer:

================
Developer corner
================

Target group: **Developers**

.. contents:: Table of Contents
   :depth: 3
   :local:


.. _developer-variable-resolvers:

Writing own variable resolvers
==============================

With :ref:`variables <variable-resolvers>` it is possible to add
information to a process start which is resolved when submitting a form. This
extension ships some variable resolvers already, e.g. for translation or
language information.

You can write your own variable resolvers dependent on your needs. Variable
resolvers are implemented as :ref:`PSR-14 event listeners
<t3api:EventDispatcher>`.

The event listener receives the event
:php:`JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent`. It
provides the following methods:

.. option:: getFieldType(): \JobRouter\AddOn\Typo3Base\Enumeration\FieldType

.. versionchanged:: 2.0.0

Get the field type, like :php:`FieldType::Text` for text or
:php:`FieldType::Integer` for int. Have a look in the class
:php:`JobRouter\AddOn\Typo3Base\Enumeration\FieldType` for the available field
types.

.. option:: getValue(): string

Get the current value of the field. One or more variables can be defined inside.

.. option:: setValue(string $value): void

Set the new value after resolving one or more variables.

.. option:: getCorrelationId(): string

Get the current correlation ID.

.. option:: getFormValues(): array

Get the form values, e.g. :php:`['company' => 'Acme Ltd.', 'name' => 'John Smith']`.

.. option:: getRequest(): \\Psr\\Http\\Message\\ServerRequestInterface

Get the current request.

.. hint::

   Some variable resolvers are already shipped with the extension. Have a look
   into the folder :file:`Classes/Domain/VariableResolver` for implementation
   details.

Example
-------

As an example we want to resolve a variable to a cookie value.

.. rst-class:: bignums-xxl

#. Create the event listener

   ::

      <?php
      declare(strict_types=1);

      namespace YourVender\YourExtension\EventListener;

      use JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent;
      use Psr\Http\Message\ServerRequestInterface;

      final class TheCookieVariableResolver
      {
         private const COOKIE_NAME = 'the_cookie';
         private const VARIABLE = '{__theCookieValue}';

         public function __invoke(ResolveFinisherVariableEvent $event): void
         {
            $value = $event->getValue();

            if (str_pos($value, self::VARIABLE) === false) {
               // Variable is not available, do nothing
               return;
            }

            $cookies = $event->getRequest()->getCookieParams();

            $variableValue = $cookies[self::COOKIE_NAME] ?? '';
            $value = str_replace(self::VARIABLE, $variableValue, $value);

            $event->setValue($value);
         }
      }

   .. important::

      Variables have to start with `{__`. Otherwise the variable resolver is not
      called for a value.


#. Register your event listener in :file:`Configuration/Services.yaml`

   .. code-block:: yaml

      services:
         YourVendor\YourExtension\EventListener\TheCookieVariableResolver:
            tags:
               - name: event.listener
                 identifier: 'your-extension/cookie-variable-resolver'
                 event: JobRouter\AddOn\Typo3Base\Event\ResolveFinisherVariableEvent

