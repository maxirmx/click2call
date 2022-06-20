<?php
 require('./click2call.php');
?>
<!DOCTYPE html>
  <html lang="ru">
    <head> 
      <script src="http://yui.yahooapis.com/3.10.3/build/yui/yui-min.js"></script>
      <link rel="stylesheet" type="text/css" href="assets/showAJ.css">
    </head>
    <body>
      <div class="example yui3-skin-sam">
       <div id="click2call" style="visibility:hidden">
         <ul>
           <li><a href="#clientTab">Clients</a></li>
           <li><a href="#widgetByTokenTab">Widgets by token</a></li>
           <li><a href="#widgetByURL">Widgets by URL</a></li>
           <li><a href="#status">Status</a></li>
         </ul>
         <div>
           <div id="clientTab">
             <br />
             <button id="addClientButton">Add client</button>
             <br /><br />
             <div id="clientDiv"></div>
             <br />
             <div id="clientPanel">
               <div class="yui3-widget-bd">
                 <form>
                   <fieldset>
                      <p>
                         <label for="token">Token&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="token" id="token_cp" placeholder=""/>
                      </p> 
                      <p>
                         <label for="login">Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="login" id="login_cp" value="" placeholder=""/>
                      </p> 
                      <p>
                         <label for="password">Password</label>
                         <input type="text" name="password" id="password_cp" value="" placeholder=""/>
                      </p> 
                   </fieldset>
                 </form>
               </div>
              </div>
           </div>
           <div id="widgetByTokenTab">
             <br />
             <button id="addWidgetByTokenButton">Add widget</button>
             <br /><br />
             <div id="widgetByTokenDiv"></div>
             <br />
             <div id="widgetByTokenPanel">
               <div class="yui3-widget-bd">
                 <form>
                   <fieldset>
                      <p>
                         <label for="token" id="token_wt_l">Token&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="token" id="token_wt" placeholder=""/>
                      </p> 
                      <p>
                         <label for="callee">Callee&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="callee" id="callee_wt" value="" placeholder=""/>
                      </p> 
                      <p>
                         <label for="login" id="login_wt_l">Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="login" id="login_wt" value="" placeholder=""/>
                      </p> 
                      <p>
                         <label for="password" id="password_wt_l">Password</label>
                         <input type="text" name="password" id="password_wt" value="" placeholder=""/>
                      </p> 
                   </fieldset>
                 </form>
               </div>
              </div>
           </div>
           <div id="widgetByURLTab">
             <br />
             <button id="addWidgetByURLButton">Add widget</button>
             <br /><br />
             <div id="widgetByURLDiv"></div>
             <br />
             <div id="widgetByURLPanel">
               <div class="yui3-widget-bd">
                 <form>
                   <fieldset>
                      <p>
                         <label for="token" id="token_wu_l">Token&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="token" id="token_wu" placeholder=""/>
                      </p> 
                      <p>
                         <label for="URL" id="URL_l">URL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="URL" id="URL" placeholder=""/>
                      </p> 
                      <p>
                         <label for="callee">Callee&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="callee" id="callee_wu" value="" placeholder=""/>
                      </p> 
                      <p>
                         <label for="login" id="login_wu_l">Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                         <input type="text" name="login" id="login_wu" value="" placeholder=""/>
                      </p> 
                      <p>
                         <label for="password" id="password_wu_l">Password</label>
                         <input type="text" name="password" id="password_wu" value="" placeholder=""/>
                      </p> 
                   </fieldset>
                 </form>
               </div>
              </div>
           </div>
           <div id="statusTab">
             <br />
              <div id="statusDiv"></div>
             <br />
           </div>
         </div>

       </div>
 
       <script>

         YUI().use('node', 'panel', 'button', 'tabview', 'datatable', 'datatable-mutable', 
                   'model', 'model-list', 'json', 'io-base', 'dd-plugin', 'autocomplete', 
                   'autocomplete-highlighters', 'autocomplete-filters', function(Y) {

           var errBox = new Y.Panel({
                          contentBox : Y.Node.create('<div id="errBox" />'),
                          bodyContent: '<div class="message icon-error"></div>',
                          width      : 410,
                          zIndex     : 6,
                          centered   : true,
                          modal      : true, 
                          render     : '.example',
                          visible    : false, // make visible explicitly with .show()
                          buttons    : {
                                          footer: 
                                          [
                                            { name  : 'proceed', label : 'OK',     action: 'onOK'     }
                                          ]
                                       }
                         });

           errBox.onOK     = function (e) 
                             {
                                e.preventDefault();
                                this.hide();
                                if (this.callback) { this.callback(); }
                                this.callback = false;
                             }

           var errorMessage = function (data, response)
                              {
                                var eb  = Y.one('#errBox .message'), 
                                    txt =  data[0].errMsg + ' (' + data[0].error + ')';
                                
                                if (response) 
                                {
                                    txt = txt + '<br />HTTP status: ' + response.statusText + ' (' + response.status +')';
                                } 
                                eb.setHTML(txt);
                                eb.set('className', 'message icon-error');
                                errBox.show();
                              } 
 
           var msgBox = new Y.Panel({
                          contentBox : Y.Node.create('<div id="msgBox" />'),
                          bodyContent: '<div class="message icon-warn"></div>',
                          width      : 500,
                          zIndex     : 6,
                          centered   : true,
                          modal      : true, 
                          render     : '.example',
                          visible    : false, // make visible explicitly with .show()
                          buttons    : {
                                          footer: 
                                          [
                                            { name  : 'cancel',  label : 'Cancel', action: 'onCancel' },
                                            { name  : 'proceed', label : 'OK',     action: 'onOK'     }
                                          ]
                                       }
                         });

           msgBox.onCancel = function (e) 
                             {
                                e.preventDefault();
                                this.hide();
                                this.callback = false;
                             }

           msgBox.onOK     = function (e) 
                             {
                                e.preventDefault();
                                this.hide();
                                if (this.callback) { this.callback(); }
                                this.callback = false;
                             }

// --------------------------------- Add/Edit dialogs ---------------------------------
// ------------------------------------- Client ---------------------------------------
           var clientPanel = new Y.Panel({
                 srcNode      : '#clientPanel',
                 width        : 350,
                 zIndex       : 5,
                 centered     : true,
                 modal        : true,
                 visible      : false,
                 render       : true,
                 plugins      : [Y.Plugin.Drag]
            });

            var tokenField_cp    = Y.one('#token_cp'),
                loginField_cp    = Y.one('#login_cp'),
                passwordField_cp = Y.one('#password_cp');

            clientPanel.addButton({ name : 'okButton_cp', section: Y.WidgetStdMod.FOOTER });
            clientPanel.addButton({ 
                value: 'Cancel', 
                section: Y.WidgetStdMod.FOOTER,
                action: function (e) 
                        { 
                          e.preventDefault(); 
                          clientPanel.hide(); 
                          tokenField_cp.set('value','');
                          loginField_cp.set('value','');
                          passwordField_cp.set('value','');
                        } 
            });

// --------------------------------- Widget by token ----------------------------------
           var widgetByTokenPanel = new Y.Panel({
                 srcNode      : '#widgetByTokenPanel',
                 width        : 350,
                 zIndex       : 5,
                 centered     : true,
                 modal        : true,
                 visible      : false,
                 render       : true,
                 plugins      : [Y.Plugin.Drag]
            });

            var tokenField_wt    = Y.one('#token_wt'),
                tokenLabel_wt    = Y.one('#token_wt_l'),
                calleeField_wt   = Y.one('#callee_wt'),
                loginField_wt    = Y.one('#login_wt'),
                loginLabel_wt    = Y.one('#login_wt_l'),
                passwordField_wt = Y.one('#password_wt'),
                passwordLabel_wt = Y.one('#password_wt_l');

            widgetByTokenPanel.addButton({ name : 'okButton_wt', section: Y.WidgetStdMod.FOOTER });
            widgetByTokenPanel.addButton({ 
                value: 'Cancel', 
                section: Y.WidgetStdMod.FOOTER,
                action: function (e) 
                        { 
                          e.preventDefault(); 
                          widgetByTokenPanel.hide(); 
                          tokenField_wt.set('value','');
                          calleeField_wt.set('value','');
                          loginField_wt.set('value','');
                          passwordField_wt.set('value','');
                        } 
            });

// ---------------------------------- Widget by URL -----------------------------------
           var widgetByURLPanel = new Y.Panel({
                 srcNode      : '#widgetByURLPanel',
                 width        : 350,
                 zIndex       : 5,
                 centered     : true,
                 modal        : true,
                 visible      : false,
                 render       : true,
                 plugins      : [Y.Plugin.Drag]
            });

            var tokenField_wu    = Y.one('#token_wu'),
                tokenLabel_wu    = Y.one('#token_wu_l'),
                URLField         = Y.one('#URL'),
                URLLabel         = Y.one('#URL_l'),
                calleeField_wu   = Y.one('#callee_wu'),
                loginField_wu    = Y.one('#login_wu'),
                loginLabel_wu    = Y.one('#login_wu_l'),
                passwordField_wu = Y.one('#password_wu'),
                passwordLabel_wu = Y.one('#password_wu_l');

            widgetByURLPanel.addButton({ name : 'okButton_wu', section: Y.WidgetStdMod.FOOTER });
            widgetByURLPanel.addButton({ 
                value: 'Cancel', 
                section: Y.WidgetStdMod.FOOTER,
                action: function (e) 
                        { 
                          e.preventDefault(); 
                          widgetByURLPanel.hide(); 
                          tokenField_wu.set('value','');
                          URLField.set('value','');
                          calleeField_wu.set('value','');
                          loginField_wu.set('value','');
                          passwordField_wu.set('value','');
                        } 
            });


// ------------------------------------- Models ---------------------------------------
// ------------------------------------- Client ---------------------------------------
           Y.client = Y.Base.create('client', Y.Model, [], { }, 
                                    { ATTRS:  { token: {}, login: {}, password: { value: 'secret' } } }
                                   );

// --------------------------------- Widget by token ----------------------------------
           Y.widgetByToken = Y.Base.create('widgetByToken', Y.Model, [], { },
                                           { ATTRS: { token: {}, callee: {}, client_id: {} } }
                                          );

// ---------------------------------- Widget by URL -----------------------------------
           Y.widgetByURL = Y.Base.create('widgetByURL', Y.Model, [], { }, 
                                         { ATTRS: { token: {}, URL: {}, callee: {}, client_id: {} } }
                                        );


// ----------------------------------- Model lists -------------------------------------
           Y.click2callList = Y.Base.create('click2callList', Y.ModelList, [], {
             _sort: function (a,b)
                    {
                       ac = parseInt(a.get('id'));
                       bc = parseInt(b.get('id'));
                       return ac < bc ? -1 : (ac > bc ? 1 : 0);
                    },
             sync:  function (action, options, callback) 
                    {
                       var data, 
                           err = null,
                           response;
                       if (action === 'read') 
                       {
                          try          
                          { 
                             response = Y.io('xhr.php', { sync: true, data: this.loadCmd }); 
                             data = Y.JSON.parse(response.responseText) || [ { error: -125 ,  errMsg: 'Invalid server response' } ];
                          }
                          catch (exc)  
                          { 
                             data = [ { error: -125 ,  errMsg: 'Invalid server response' } ];  
                          }
                          if (data && data[0] && data[0].error<0)
                          {
                              errorMessage(data, response);
                              data = [];
                          }
                       } 
                       else 
                       {
                          err = 'Invalid action';
                       }
                       if (Y.Lang.isFunction(callback)) { callback(err, data); }
                    }
           },
           {
             ATTRS: { 
                      loadCmd:   { }
                    }
           });

           Y.clientList = Y.Base.create('clientList', Y.click2callList, [], {
             model: Y.client,
             loadCmd:   'load=clients'
           });

           Y.widgetByTokenList = Y.Base.create('widgetByTokenList', Y.click2callList, [], {
             model: Y.widgetByToken,
             loadCmd: 'load=widgetsByToken'
           });
    
         Y.widgetByURLList = Y.Base.create('widgetByURLList', Y.click2callList, [], {
             model: Y.widgetByURL,
             loadCmd: 'load=widgetsByURL'
           });
    
            function Editable() 
            {
            }

            Editable.ATTRS =
            {
                editIndex:         { },
                deleteIndex:       { },
                editRecord:        { },
                deleteRecord:      { }
            }

            var click2callDataTable = Y.Base.create("DataTable", Y.DataTable, [Editable]);

            var tabview = new Y.TabView({srcNode:'#click2call'});
            var clientTable = new Y.DataTable({
                columns: [
                  { key: 'id'      }, 
                  { key: 'token'   }, 
                  { key: 'login'   }, 
                  { key: 'secret', label: 'password', formatter: 'secret'  },
                  { key: '-edit-',   formatter: function(o) { o.className += ' edit-button' } },
                  { key: '-delete-', formatter: function(o) { o.className += ' delete-button' } }
                ],
                data:    new Y.clientList,
                editIndex  : 4,
                deleteIndex: 5,
                editRecord : function(r)  
                             {
                                var b = clientPanel.getButton('okButton_cp');
                                b.set('label','Save');
                                tokenField_cp.set('value',r.get('token'));
                                loginField_cp.set('value',r.get('login'));
                                passwordField_cp.set('value',r.get('secret'));
                                b.detach('click');
                                b.on('click', function(e, r) 
                                              {
                                                var response,
                                                    data ;
                                                e.preventDefault();
                                                clientPanel.hide();
               
                                                response = Y.io('xhr.php', 
                                                   { 
                                                      sync: true, 
                                                      data: 'update=client&token=' + encodeURIComponent(r.get('token')) + 
                                                                         '&new_token=' + encodeURIComponent(tokenField_cp.get('value')) + 
                                                                         '&login=' + encodeURIComponent(loginField_cp.get('value')) + 
                                                                         '&password=' + encodeURIComponent(passwordField_cp.get('value'))
                                                   }
                                                ); 
                                                try          { data = Y.JSON.parse(response.responseText);                         }
                                                catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
                                                if (data && data[0] && data[0].error<0)
                                                {
                                                   errorMessage(data, response);
                                                }
                                                else 
                                                {
                                                   if (r.get('token') != tokenField_cp.get('value'))
                                                   {
                                                      widgetByTokenTable.data.load(function () { widgetByTokenTable.data.sort(); });
                                                      widgetByURLTable.data.load(function ()   { widgetByURLTable.data.sort();   });
                                                   }
                                                   r.set('token', tokenField_cp.get('value'));
                                                   r.set('login', loginField_cp.get('value'));
                                                   r.set('secret', passwordField_cp.get('value'));
                                                } 
                                                tokenField_cp.set('value','');
                                                loginField_cp.set('value','');
                                                passwordField_cp.set('value','');
                                              }, b, r);
                                clientPanel.set('headerContent', 'Edit client (id:' + r.get('id') +')');
                                clientPanel.show();
                              },
                deleteRecord: function(r) 
                              {
                                 var mb = Y.one('#msgBox .message');
                                 mb.setHTML('Are you sure you want to delete client with token:\'' + r.get('token') + 
                                            '\' (id:' + r.get('id') +') and all widgets for this token ?');
                                 mb.set('className', 'message icon-question');
                                 msgBox.callback = function() { clientTable.removeRow(r); } 
                                 msgBox.show();
                              }
            });

            var widgetByTokenTable = new click2callDataTable({
                columns: [
                  { key: 'id' } , 
                  { key: 'token', label: 'client', 
                    formatter: function (o) {
                      return o.record.get('token') +' (id:' + o.record.get('client_id') + ')';
                    }
                  }, 
                  { key: 'callee' },
                  { key: '-edit-',   formatter: function(o) { o.className += ' edit-button' } },
                  { key: '-delete-', formatter: function(o) { o.className += ' delete-button' } }
                ],
                data:    new Y.widgetByTokenList,
                editIndex  : 3,
                deleteIndex: 4,
                editRecord : function(r)  {
                                var b = widgetByTokenPanel.getButton('okButton_wt');
                                b.set('label','Save');
                                tokenField_wt.set('value',r.get('token'));
                                tokenField_wt.hide();
                                tokenLabel_wt.hide();
                                loginField_wt.hide();
                                loginLabel_wt.hide();
                                passwordField_wt.hide();
                                passwordLabel_wt.hide();
                                calleeField_wt.set('value',r.get('callee'));
                                b.detach('click');
                                b.on('click', function(e, r) 
                                              {
                                                var response,
                                                    data ;
                                                e.preventDefault();
                                                widgetByTokenPanel.hide();
               
                                                response = Y.io('xhr.php', 
                                                   { 
                                                      sync: true, 
                                                      data: 'update=widget&token=' + encodeURIComponent(tokenField_wt.get('value')) + 
                                                                         '&callee=' + encodeURIComponent(calleeField_wt.get('value'))  
                                                   }
                                                ); 
                                                try          { data = Y.JSON.parse(response.responseText);                         }
                                                catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
                                                if (data && data[0] && data[0].error<0)
                                                {
                                                   errorMessage(data, response);
                                                }
                                                else 
                                                {
                                                   r.set('callee', calleeField_wt.get('value'));
                                                } 
                                                tokenField_wt.set('value','');
                                                calleeField_wt.set('value','');
                                              }, b, r);
                                widgetByTokenPanel.set('headerContent', 'Edit widget for token:\'' + r.get('token') +'\'');
                                widgetByTokenPanel.show();
                },
                deleteRecord: function(r) {
                   var mb = Y.one('#msgBox .message');
                   mb.setHTML('Are you sure you want to delete widget for token:\'' + r.get('token') + 
                              '\' (client id:' + r.get('client_id') +') ?');
                   mb.set('className', 'message icon-question');
                   msgBox.callback = function() { widgetByTokenTable.removeRow(r); } 
                   msgBox.show();
                }
            });

            var widgetByURLTable = new click2callDataTable({
                columns: [
                  { key: 'id' } , 
                  { key: 'token', label: 'client', 
                    formatter: function (o) {
                      return o.record.get('token') +' (id:' + o.record.get('client_id') + ')';
                    }
                  }, 
                  { key: 'URL' },
                  { key: 'callee' },
                  { key: '-edit-',   formatter: function(o) { o.className += ' edit-button' } },
                  { key: '-delete-', formatter: function(o) { o.className += ' delete-button' } }
                ],
                data:    new Y.widgetByURLList,
                editIndex  : 4,
                deleteIndex: 5,
                editRecord : function(r)  {
                                var b = widgetByURLPanel.getButton('okButton_wu');
                                b.set('label','Save');
                                URLField.set('value',r.get('URL'));
                                URLField.hide();
                                URLLabel.hide();
                                tokenField_wu.hide();
                                tokenLabel_wu.hide();
                                loginField_wu.hide();
                                loginLabel_wu.hide();
                                passwordField_wu.hide();
                                passwordLabel_wu.hide();
                                calleeField_wu.set('value',r.get('callee'));
                                b.detach('click');
                                b.on('click', function(e, r) 
                                              {
                                                var response,
                                                    data ;
                                                e.preventDefault();
                                                widgetByURLPanel.hide();
               
                                                response = Y.io('xhr.php', 
                                                   { 
                                                      sync: true, 
                                                      data: 'update=widget&URL=' + encodeURIComponent(URLField.get('value')) + 
                                                                         '&callee=' + encodeURIComponent(calleeField_wu.get('value'))  
                                                   }
                                                ); 
                                                try          { data = Y.JSON.parse(response.responseText);                         }
                                                catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
                                                if (data && data[0] && data[0].error<0)
                                                {
                                                   errorMessage(data, response);
                                                }
                                                else 
                                                {
                                                   r.set('callee', calleeField_wu.get('value'));
                                                } 
                                                URLField.set('value','');
                                                calleeField_wu.set('value','');
                                              }, b, r);
                                widgetByURLPanel.set('headerContent', 'Edit widget for URL:\'' + r.get('URL') +'\' and token:\'' +
                                                                      r.get('token') +'\'' );
                                widgetByURLPanel.show();
                },
                deleteRecord: function(r) {
                   var mb = Y.one('#msgBox .message');
                   mb.setHTML('Are you sure you want to delete widget for token:\'' + r.get('token') + 
                              '\' (client id:' + r.get('client_id') +') and URL \'' + r.get('URL') + '\' ?');
                   mb.set('className', 'message icon-question');
                   msgBox.callback = function() { widgetByURLTable.removeRow(r); } 
                   msgBox.show();
                }
            });

           var statusTable = new Y.DataTable({
                columns: ['Module', 'Version', 'Error'],
<?php
 $rwd = new WDb();
 print  "        data   : [ \n";
 print  "{ Module: \"PHP\", Version: \"" . PHP_VERSION ."\", Error: \"".$rwd->errorMessage(WDb::RWD_OK)." (". WDb::RWD_OK .")\" },\n";
 print  "{ Module: \"PDO\", Version: \"" . phpversion('PDO') ."\", Error: \"".$rwd->errorMessage(WDb::RWD_OK)." (". WDb::RWD_OK .")\" },\n";
 $res = $rwd->Connect();      
 if ($res == WDb::RWD_OK)
 {
   print  "{ Module: \"SQLite\", Version: \"".$rwd->showSQLiteVersion()."\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
   print  "{ Module: \"Database schema\", Version: \"".$rwd->showDatabaseVersion()."\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
 }
 else
 {
   print  "{ Module: \"SQLite\", Version: \"Unknown\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
   print  "{ Module: \"Database schema\", Version: \"Unknown\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
 }
 print  "{ Module: \"Database script\", Version: \"".$rwd->showScriptVersion()."\", Error: \"".$rwd->errorMessage(0)." (". 0 .")\" } \n]\n";
?>
           });

            tabview.render();
            clientTable.render("#clientDiv"); 
            widgetByTokenTable.render("#widgetByTokenDiv"); 
            widgetByURLTable.render("#widgetByURLDiv"); 
            statusTable.render("#statusDiv"); 


// --------------------------------- Add/Edit buttons ---------------------------------
// ------------------------------------- Client ---------------------------------------
     
            var addClientButton = new Y.Button({
                srcNode:'#addClientButton',
                on:  
                {
                  'click': function()
                           {
                             var b = clientPanel.getButton('okButton_cp');
                             b.set('label','Add');
                             b.detach('click');
                             b.on('click', function(e) 
                                           {
                                             e.preventDefault();
                                             clientTable.data.add({
                                                    token : tokenField_cp.get('value'),
                                                    login : loginField_cp.get('value'),
                                                    secret: passwordField_cp.get('value')
                                             }).save;
                                             clientPanel.hide();
                                             tokenField_cp.set('value','');
                                             loginField_cp.set('value','');
                                             passwordField_cp.set('value','');
                                           });
                             clientPanel.set('headerContent', 'Add new client');
                             clientPanel.show();
                           }
                }
            }).render();

// --------------------------------- Widget by token ----------------------------------

            var addWidgetByTokenButton = new Y.Button({
                srcNode:'#addWidgetByTokenButton',
                on: 
                {
                  'click': function()
                           {
                             var b = widgetByTokenPanel.getButton('okButton_wt');
                             b.set('label','Add');
                             b.detach('click');
                             b.on('click', function(e) 
                                           {
                                             e.preventDefault();
                                             widgetByTokenTable.data.add({
                                                    token : tokenField_wt.get('value'),
                                                    callee: calleeField_wt.get('value'),
                                                    id    : loginField_wt.get('value'),
                                                    client_id : passwordField_wt.get('value')
                                             }).save;
                                             widgetByTokenPanel.hide();
                                             tokenField_wt.set('value','');
                                             calleeField_wt.set('value','');
                                             loginField_wt.set('value','');
                                             passwordField_wt.set('value','');
                                           });
                             widgetByTokenPanel.set('headerContent', 'Add new widget for token');
                             tokenField_wt.show();
                             tokenLabel_wt.show();
                             loginField_wt.show();
                             loginLabel_wt.show();
                             passwordField_wt.show();
                             passwordLabel_wt.show();
                             widgetByTokenPanel.show();
                           }
                }
            }).render();

// ---------------------------------- Widget by URL -----------------------------------

            var addWidgetByURLButton = new Y.Button({
                srcNode:'#addWidgetByURLButton',
                on: 
                {
                  'click': function()
                           {
                             var b = widgetByURLPanel.getButton('okButton_wu');
                             b.set('label','Add');
                             b.detach('click');
                             b.on('click', function(e) 
                                           {
                                             e.preventDefault();
                                             widgetByURLTable.data.add({
                                                    token : tokenField_wu.get('value'),
                                                    URL: URLField.get('value'),
                                                    callee: calleeField_wu.get('value'),
                                                    id    : loginField_wu.get('value'),
                                                    client_id : passwordField_wu.get('value')
                                             }).save;
                                             widgetByURLPanel.hide();
                                             tokenField_wu.set('value','');
                                             URLField.set('value','');
                                             calleeField_wu.set('value','');
                                             loginField_wu.set('value','');
                                             passwordField_wu.set('value','');
                                           });
                             widgetByURLPanel.set('headerContent', 'Add new widget for URL');
                             tokenField_wu.show();
                             tokenLabel_wu.show();
                             URLField.show();
                             URLLabel.show();
                             loginField_wu.show();
                             loginLabel_wu.show();
                             passwordField_wu.show();
                             passwordLabel_wu.show();
                             widgetByURLPanel.show();
                           }
                }
            }).render();

            clientTable.data.load(function () { clientTable.data.sort(); });
            widgetByTokenTable.data.load(function () { widgetByTokenTable.data.sort(); });
            widgetByURLTable.data.load(function () { widgetByURLTable.data.sort(); });

// ------------------------------- Model event handlers -------------------------------
// ------------------------------------- Client ---------------------------------------
            clientTable.data.on('remove', function(e) {
               var client = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', { sync: true, data: 'delete=client&token=' + encodeURIComponent(client.get('token')) }); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 ,  errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
               else 
               {
                   widgetByTokenTable.data.load(function () { widgetByTokenTable.data.sort(); });
                   widgetByURLTable.data.load(function ()   { widgetByURLTable.data.sort();   });
               } 
            });  

            clientTable.data.on('add', function(e) {
               var client = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', 
                                { 
                                   sync: true, 
                                   data: 'insert=client&token=' + encodeURIComponent(client.get('token')) + 
                                                      '&login=' + encodeURIComponent(client.get('login')) + 
                                                      '&password=' + encodeURIComponent(client.get('password'))
                                }
                              ); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
               else 
               {
                   client.set('id',data[0].id);
               } 
            });  

// --------------------------------- Widget by token ----------------------------------
            widgetByTokenTable.data.on('remove', function(e) {
               var widget = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', { sync: true, data: 'delete=widget&token=' + encodeURIComponent(widget.get('token')) }); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 ,  errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
            });  

            widgetByTokenTable.data.on('add', function(e) {
               var widget = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', 
                                { 
                                   sync: true, 
                                   data: 'insert=widget&token=' + encodeURIComponent(widget.get('token')) + 
                                                      '&callee=' + encodeURIComponent(widget.get('callee')) +
                                                      '&login=' + encodeURIComponent(widget.get('id')) +
                                                      '&password=' + encodeURIComponent(widget.get('client_id')) 
                                }
                              ); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
               else 
               {
                   widget.set('id',data[0].id);
                   widget.set('client_id',data[0].client_id);
                   clientTable.data.load(function () { clientTable.data.sort(); });
               } 
            });  

// ---------------------------------- Widget by URL -----------------------------------
            widgetByURLTable.data.on('remove', function(e) {
               var widget = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', { sync: true, data: 'delete=widget&URL=' + encodeURIComponent(widget.get('URL')) }); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 ,  errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
            });  

            widgetByURLTable.data.on('add', function(e) {
               var widget = e.model,
                   response,
                   data ;
               
               response = Y.io('xhr.php', 
                                { 
                                   sync: true, 
                                   data: 'insert=widget&token=' + encodeURIComponent(widget.get('token')) + 
                                                      '&URL=' + encodeURIComponent(widget.get('URL')) +
                                                      '&callee=' + encodeURIComponent(widget.get('callee')) +
                                                      '&login=' + encodeURIComponent(widget.get('id')) +
                                                      '&password=' + encodeURIComponent(widget.get('client_id')) 
                                }
                              ); 
               try          { data = Y.JSON.parse(response.responseText);                         }
               catch (exc)  { data = [ { error: -125 , errMsg: 'Invalid server response' } ];  }
               if (data && data[0] && data[0].error<0)
               {
                   e.preventDefault();
                   errorMessage(data, response);
               }
               else 
               {
                   widget.set('id',data[0].id);
                   widget.set('client_id',data[0].client_id);
                   clientTable.data.load(function () { clientTable.data.sort(); });
               } 
            });  

// -------------------------------- Cell event handler --------------------------------
            var onClick = function(e)
                          {
                             var cellIndex = e.currentTarget.get('cellIndex'),
                                 record = this.getRecord(e.target);
                                 if      (cellIndex == this.get('editIndex'))      { this.get('editRecord')(record);   }
                                 else if (cellIndex == this.get('deleteIndex'))    { this.get('deleteRecord')(record); }
                          };
  
            Y.one('#clientDiv').delegate('click', onClick, 'td', clientTable);
            Y.one('#widgetByTokenDiv').delegate('click', onClick, 'td', widgetByTokenTable);
            Y.one('#widgetByURLDiv').delegate('click', onClick, 'td', widgetByURLTable);

// ---------------------------------- Autocompleters ----------------------------------
            Y.one('#token_wt').plug(Y.Plugin.AutoComplete, {
                         resultHighlighter: 'phraseMatch',
                         resultListLocator: function (t) { return clientTable.data.toArray(); },
                         resultTextLocator: function (r) { return r.get('token'); }, 
                         resultFilters: 'phraseMatch',
                         source: clientTable,
                         on: {
                               'results' : function(e)
                                {
                                   if (e.results.length > 0)
                                   {
                                     loginField_wt.set('disabled', true);
                                     passwordField_wt.set('disabled', true);
                                     loginLabel_wt.setStyle('color', loginField_wt.getStyle('color'));
                                     passwordLabel_wt.setStyle('color', passwordField_wt.getStyle('color'));
                                   }
                                   else
                                   {
                                     loginField_wt.set('disabled', false);
                                     passwordField_wt.set('disabled', false);
                                     loginLabel_wt.setStyle('color', loginField_wt.getStyle('color'));
                                     passwordLabel_wt.setStyle('color', passwordField_wt.getStyle('color'));
                                   }
                                }
                             }
                        
            });
            
            Y.one('#token_wu').plug(Y.Plugin.AutoComplete, {
                         resultHighlighter: 'phraseMatch',
                         resultListLocator: function (t) { return clientTable.data.toArray(); },
                         resultTextLocator: function (r) { return r.get('token'); }, 
                         resultFilters: 'phraseMatch',
                         source: clientTable,
                         on: {
                               'results' : function(e)
                                {
                                   if (e.results.length > 0)
                                   {
                                     loginField_wu.set('disabled', true);
                                     passwordField_wu.set('disabled', true);
                                     loginLabel_wu.setStyle('color', loginField_wu.getStyle('color'));
                                     passwordLabel_wu.setStyle('color', passwordField_wu.getStyle('color'));
                                   }
                                   else
                                   {
                                     loginField_wu.set('disabled', false);
                                     passwordField_wu.set('disabled', false);
                                     loginLabel_wu.setStyle('color', loginField_wu.getStyle('color'));
                                     passwordLabel_wu.setStyle('color', passwordField_wu.getStyle('color'));
                                   }
                                }
                             }
            });
  


// ---------------------------------- GO SYSIN DD * ----------------------------------
            Y.one('#click2call').setStyle('visibility','visible');

         });

       </script>
     </div>
   </body>
</html>