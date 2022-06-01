.. include:: _includes.rst.txt

.. _installation:

============
Installation
============

Target group: **Administrators**

.. contents::
   :depth: 1
   :local:


.. _installation-requirements:

Requirements
============

The extension is available for TYPO3 v10 LTS and TYPO3 v11 LTS.


.. _version-matrix:

Version matrix
==============

============== ========== ===========
JobRouter Base PHP        TYPO3
============== ========== ===========
1.0            7.2 - 7.4  10.4
-------------- ---------- -----------
1.1 / 1.2      7.3 - 8.1  10.4 / 11.5
-------------- ---------- -----------
1.3            7.4 - 8.1  10.4 / 11.5
============== ========== ===========


.. _installation-composer:

Installation via composer
=========================

.. note::
   Since this extension is a dependency in :ref:`TYPO3 JobRouter Data
   <typo3-jobrouter-data:introduction>` and :ref:`TYPO3 JobRouter Process
   <typo3-jobrouter-process:introduction>`, it does not need to be installed
   manually.

The recommended way to install this extension is by using Composer. In your
Composer-based TYPO3 project root, just type:

.. code-block:: shell

   composer req brotkrueml/typo3-jobrouter-base

and the recent version will be installed.


.. _installation-extension-manager:

Installation in Extension Manager
=================================

You can also install the extension from the `TYPO3 Extension Repository (TER)`_.
See :ref:`t3start:extensions_legacy_management` for a manual how to
install an extension.


.. _TYPO3 Extension Repository (TER): https://extensions.typo3.org/extension/jobrouter_base/
