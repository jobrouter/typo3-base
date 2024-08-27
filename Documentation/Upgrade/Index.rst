.. _upgrade:

=======
Upgrade
=======

From version 3.0 to 4.0
=======================

The following :ref:`language variables <variable-resolvers-language>` have been
removed:

*  `{__language.twoLetterIsoCode}`, use `{__language.locale.languageCode}`
   instead
*  `{__language.direction}` (without substitution)


From version 2.0 to 3.0
=======================

The namespace of the JobRouter TYPO3 Base classes has changed from

.. code-block:: plaintext

   \Brotkrueml\JobRouterBase

to

.. code-block:: plaintext

   \JobRouter\Addon\Typo3Base

The easiest way to update your code to the new namespace is to use
search/replace in your project.

The package name (used in :file:`composer.json`) has changed from
`brotkrueml/jobrouter-typo3-base` to `jobrouter/typo3-base`.
