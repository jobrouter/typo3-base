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

.. note::
   The extension in version |release| supports TYPO3 v12 LTS and TYPO3 v13 LTS.


.. _version-matrix:

Version matrix
==============

============== ========== ===========
JobRouter Base PHP        TYPO3
============== ========== ===========
4.0            8.1 - 8.4  12.4 / 13.4
-------------- ---------- -----------
3.0            8.1 - 8.3  11.5 / 12.4
-------------- ---------- -----------
2.0            8.1 - 8.3  11.5 / 12.4
-------------- ---------- -----------
1.4            7.4 - 8.2  10.4 / 11.5
-------------- ---------- -----------
1.3            7.4 - 8.1  10.4 / 11.5
-------------- ---------- -----------
1.1 / 1.2      7.3 - 8.1  10.4 / 11.5
-------------- ---------- -----------
1.0            7.2 - 7.4  10.4
============== ========== ===========


.. _installation-composer:

Installation via Composer
=========================

.. note::
   Since this extension is a dependency in :ref:`TYPO3 JobRouter Data
   <ext_jobrouter_data:introduction>` and :ref:`TYPO3 JobRouter Process
   <ext_jobrouter_process:introduction>`, it does not need to be installed
   manually.

The recommended way to install this extension is by using Composer. In your
Composer-based TYPO3 project root, just type:

.. code-block:: shell

   composer req jobrouter/typo3-base

and the recent version will be installed.


.. _installation-extension-manager:

Installation in Extension Manager
=================================

You can also install the extension from the `TYPO3 Extension Repository (TER)`_.
See :ref:`t3start:extensions-legacy-management` for a manual how to
install an extension.


.. _TYPO3 Extension Repository (TER): https://extensions.typo3.org/extension/jobrouter_base/
