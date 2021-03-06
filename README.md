# php7-cascade-ws-ns
<p>This is the new home of SUNY Upstate's Cascade web service library.</p>

<p>This library was started by and almost exclusively built by <a href="https://github.com/wingmingchan">Wing Ming Chan</a>. We would like to extend a huge thank you to Wing for all of his hard work and dedication in making this the most comprehensive tool for interacting with Cascade CMS's web services.</p>

<p>Wing is a huge proponent of creating reusable code which adheres to OOP best practices and we will uphold those values as we continue updating the library well after Wing retires from SUNY Upstate.</p>

<h2>About</h2>

<p>The SUNY Upstate Cascade web service library, using namespaces, written in PHP 7 for Cascade 8.11/8.12, by German Drulyk and Wing Ming Chan.</p>

<p>Last modified: 7/15/2019, 11:34 AM</p>

<p>Note that new code related to namingRuleAssets does not work for SOAP due to a bug.</p>

<p>This version of the library makes use of features in PHP 7.</p>

<h2>cascade_ws_ns7_8.13.0 is in progress.</h2>
<p>Development has begun on cascade_ws_ns7_8.13.0 which will introduce Custom Workflow Email capabilities.</p>

<h2>Purpose of the Upgrade</h2>
<p>The library has been upgraded to PHP 7 for two main reasons:</p>
<ul>
<li>When using parameter types and return types, we can have tighter control over client code</li>
<li>We can use the type information, together with the ReflectionUtility class, to generate documentation pages; for an example of a generated page, see http://www.upstate.edu/web-services/api/asset-classes/asset.php</li>
</ul>
<p>When a class is upgraded with required information, the following type of code is made possible:</p>
<pre>
echo u\ReflectionUtility::getClassDocumentation( "cascade_ws_asset\Asset", true );
u\ReflectionUtility::showMethodSignatures( "cascade_ws_asset\Asset" );
u\ReflectionUtility::showMethodSignature( "cascade_ws_asset\Asset", "edit" );
u\ReflectionUtility::showMethodDescription( "cascade_ws_asset\Asset", "edit" );
u\ReflectionUtility::showMethodExample( "cascade_ws_asset\Asset", "edit" );
</pre>
<p>The ReflectionUtility class can also be used to reveal information of any class and any function:</p>
<pre>
echo u\ReflectionUtility::getMethodSignatures( "SimpleXMLElement", true ), BR;

u\ReflectionUtility::showFunctionSignature( "str_replace", true );
</pre>

<h2>Resources</h2>
<ol>
<li>For more information, visit http://www.upstate.edu/web-services/index.php</li>
<li>Online tutorials: http://www.upstate.edu/web-services/courses/ws-online-tutorials.php</li>
<li>Online tutorial recordings:
<ul><li>The first series: https://www.youtube.com/playlist?list=PLiPcpR6GRx5cGyfQESK6ZAj4My8rJidt4</li>
<li>The second series: https://www.youtube.com/watch?v=oYndzPxZ7yg&index=1&list=PLXb2nCJdzD9B26kI9vEds-0bQoPSWj5--</li></ul></li>
<li>Examples and recipes: https://github.com/suny-upstate-cwt/php-cascade-ws-ns-examples</li>
<li>Asset classes API: http://www.upstate.edu/web-services/api/asset-classes/index.php</li>
<li>Cascade Web Services courses: http://www.upstate.edu/web-services/courses/index.php</li>
<li>Cascade Web Services Library Support: https://groups.google.com/forum/#!forum/cascade-web-services-library-support</li>
<li>Erik España: https://github.com/espanae/cascade-server-web-services</li>
<li>Mark Nokes: https://github.com/marknokes</li>
<li>Christopher John Walsh: https://gist.github.com/quantegy</li>
