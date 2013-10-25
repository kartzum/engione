Engione
===========

Engione is a web framework based on micro core principle.

Why Engione?
-----------

A lot of brilliant frameworks in web based on best practice and use famous patterns. 
But these ones contain ton code that do not use in real cases. Extends are unclear. 
Inner processes are closed. 
Some lines of real code are wrapped with boring infrastructure. 
Engione is not silver bullet, of course, but just implements some ideas for fun and fast web coding.
Engione based on: Mode View Controller (MVC) and plugins. Also support: templates and localization.
Basically the framework provides functionality for messaging among plugins. 
This approach gives isolation and good automatic testability.

Structures of an Application
-----------

Root

engione
- index.php: index of the framework
- go.php: start point of the framework
- system: system code
- plugins: the framework plugins

application
- index.php: index of the an application
- plugins: plugins of an application

Structure of a Plugin
-----------

- plugin_name: folder of a plugin
- plugin_name.php: entry point of a plugin
- templates: folder with templates
- themes: themes by name
- resources: localization data

Any plugin has 3 functions: start, execution and reset. A start function is executed only one time per session. 
A execute function is called by the framework during request. 
A reset function is executed in the end of session.
A plugin can send message through the framework and receive answer in an execution function. 

Templates
-----------

Templates are just html files and contain special tags start with <% and end with %>
- 'd': function of data binding (<%d(parameter%>)
- 'i': include function (<%i(template,parameter%>)
- 'ir': include function with repeat (<%ir(template,parameter%>)
- 'pt': include function of template (<%pt(parameter%>)
- 'c': call function <%c(function,parameter)%>
- 'ct': call plugin with text answer <%ct(plugin_function,parameter)%>

Query parameters: 

- u: command to the framework, u=r - reset the framework
- e: plugin name
- l: locale code
- a: area code
