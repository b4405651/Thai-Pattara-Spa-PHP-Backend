/*global Epitome, App */
/*jshint mootools:true */
;
(function (exports) {
	'use strict';

	var wrap = function (Model) {

		return new Class({

			Extends: Model,

			config: compojoom.ccomment.config,

			properties: {
				name: {
					get: function () {
						var name, config = this.config;
						name = config._use_name ? this._attributes.name : this._attributes.username;
						if (!name) {
							name = Cookie.read('compojoom.ccomment.user.name');
						}
						// remove the saved cookie if we are logged in
						if (this._attributes.loggedin) {
							Cookie.dispose('compojoom.ccomment.user.name');
						}
						return name;
					},
					set: function (name) {
						var config = this.config;
						if (!this._attributes.loggedin) {
							Cookie.write('compojoom.ccomment.user.name', name);
							if (config._user_name) {
								this._attributes.name = name
							} else {
								this._attributes.username = name;
							}
						}
					}
				},
				avatar: {
					get: function () {
						var avatar = this._attributes.avatar,
							email = this.get('email'),
							config = this.config;

						if (!this._attributes.loggedin) {
							if (config.gravatar) {
								if (email) {
									avatar = 'http://www.gravatar.com/avatar/' + email.toMD5();
								}
							}
						}

						return avatar;
					}
				},
				email: {
					get: function () {
						var email = this._attributes.email;
						if (!email) {
							email = Cookie.read('compojoom.ccomment.user.email');
						}
						// remove the saved cookie if we are logged in
						if (this._attributes.loggedin) {
							Cookie.dispose('compojoom.ccomment.user.email');
						}
						return email;
					},
					set: function (email) {
						if (email) {
							Cookie.write('compojoom.ccomment.user.email', email);
							this._attributes.email = email;
						}
					}
				}
			},

			options: {
				defaults: {
					username: '',
					email: '',
					notify: 1,
					loggedin: 0,
					avatar: ''
				}
			},
			getName: function () {
				return this.get('name');
			},
			getDefaultName: function () {
				var name = this.get('name');
				if (!name) {
					name = Joomla.JText._('COM_COMMENT_ANONYMOUS', 'Anonymous');
				}

				return name;
			},

			getAvatar: function () {
				return this.get('avatar');
			},

			getEmail: function () {
				return this.get('email');
			}

		});

	};

	if (typeof define === 'function' && define.amd) {
		define(['epitome/epitome-model'], wrap);
	}
	else {
		exports.Ccomment || (exports.Ccomment = {});
		exports.Ccomment.User = wrap(Epitome.Model);
	}

}(this));