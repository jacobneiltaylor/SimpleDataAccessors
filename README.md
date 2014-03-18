SimpleDataAccessors
===================

Simple PHP Traits for easy integration with relational DBMS's.
Requires PHP 5.5 or above for generators.

This is a very simple collection of traits with a common interface to allow PHP programmers to easily and quickly integrate thier classes with relational databases. It is composed of the header file (sda.inc.php) and a series of providers (well, just MySQL now, but Postgre and MSSQL are next!). It is aimed at exposing a very simple, common set of methods to PHP programmers, so they worry less about data logistics and more about making their application awesome. A secondary goal is the ability to allow drop in replacement of the backend DBMS, provided the queries are cross compatible.

FAQ
===

1. Do I need to know SQL?
Yes, you do. These traits only expose DBMS functionality in a common and simple manner. You still need to know SQL to query the database.

2. Why traits? Why not use classes and make developers inherit the functionality?
I suppose I could, but SDA was designed to be both simple and flexible. PHP's object-oriented programming implementation only supports single inheritance, and I wanted developers to be able to retrofit existing applications with SDA easily. Requiring developers to inherit SDA functionality from the top of an object hierachy would wreak havoc in some codebases. From a logical standpoint, however, database integration is a trait (in the normal sense of the word) of many objects and applications in PHP, so I believe that the use of traits is well-justified.

3. Can I implement a provider for my favourite DBMS?
You sure can! You simply need to implement the abstract functions named in the sda.inc.php file in a trait inherited from that trait. Feel free to expose any extra functionality, but please ensure that by default the provider would effectively drop in to any existing application using SDA. For example, MSSQL likes to use Windows Integrated Auth by default. This would not work with the usual username/password system exposed by SDA, so set SQL Server Auth as default but allow Windows Auth to be enabled by a special function, exclusive to your provider.
