(function() { 
     tinymce.create("tinymce.plugins.speakstaff_player_mce", {
        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {
            //add new button   
            plugins: "colorpicker", 
            ed.addButton("spstaSat_button", {
                title : spstaSatL10n.tinyMceButton,
                onclick: function() {
					var width = 600;
                    var height = 200;
                    /*var plain_block = {
						type: 'container',
						html: '<textarea style="margin: 10px; width: 550px !important; height: 450px !important; background-color: #eee;" readonly="readonly">Whatever plain text I need to show goes here</textarea>'
					};*/
					
					// Open window
					ed.windowManager.open({
						title: spstaSatL10n.windowTitle,
						width: width,
						height: height,
                        scrollbars: true,
						buttons: [
							{text: spstaSatL10n.buttonClose, onclick: 'close'},
							{text: spstaSatL10n.buttonSubmit, onclick: 'submit'},
						],
						body: [
							{type: 'container', name: 'someHelpText', html: '<div style="width: 100% !important;">'+ spstaSatL10n.idHelpText +'</div>'},
							{type: 'textbox', name: 'id', label: spstaSatL10n.idLabel},
                            {type: 'container', name: 'someHelpText', html: '<div style="width: 100% !important;"><b>'+ spstaSatL10n.settingsHelpText +'</b></div>'},
                            {type: 'listbox', 
                                name: 'width', 
                                label: spstaSatL10n.widthLabel, 
                                'values': [
                                    {text: '90%', value: '90'},
                                    {text: '85%', value: '85'},
                                    {text: '80%', value: '80'},
                                    {text: '75%', value: '75'},
                                    {text: '70%', value: '70'}
                                ]
                            },
                            {type: 'listbox', 
                                name: 'autoplay', 
                                label: spstaSatL10n.autoplayLabel, 
                                'values': [
                                    {text: spstaSatL10n.autoplayLabelOptionNo, value: 'false'},
									{text: spstaSatL10n.autoplayLabelOptionYes, value: 'true'}
                                ]
                            }
						],
						onsubmit: function(e) {
							// Insert content when the window form is submitted
                            var shortCode = '[speakstaffPlayer playerid="' + e.data.id + '"';
                            if (e.data.width.length) {
                                shortCode +=   ' width="' + e.data.width + '"';                                
                            }
                            if (e.data.autoplay.length) {
                                shortCode +=   ' autoplay="' + e.data.autoplay + '"';                                
                            }
                            shortCode +=   ']';
							ed.insertContent(shortCode);
						}
					});
                    var colorPicker = document.getElementById('colopickerMW')
                    console.log('colorPicker', colorPicker)
                    //colorPicker.wpColorPicker();
				},

                //cmd : "speakstaffPlayer",
                image : "https://s3-eu-west-1.amazonaws.com/speakstaff/plugin/speakstaff_wp.png"
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "SpeakStaff Player Button",
                author : "Albert Brückmann",
                version : "0.12"
            };
        },


    });
    tinymce.PluginManager.add("speakstaff_player_mce", tinymce.plugins.speakstaff_player_mce);
})();