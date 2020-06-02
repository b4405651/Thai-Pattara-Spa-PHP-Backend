;(function (exports) {
	'use strict';

	var wrap = function (Collection, Model) {
		return new Class({

			Extends: Collection,

			model: Model,

			options: {
				urlRoot: compojoom.ccomment.config.baseUrl + '?option=com_comment&task=comments.getcomments&format=json&lang=' + compojoom.ccomment.config.langCode +  '&contentid=' + compojoom.ccomment.item.contentid + '&component=' + compojoom.ccomment.item.component
			},

			getParent: function (model) {
				var parentId = model.get('parent_id');
				return this.findOne('#' + parentId);
			},

			postProcessor: function (json) {
				if (json.info) {
					this.info = json.info;
					json = json.models;
				}
				// apply a post-processor to response
				return json;
			}
		});

	};

	if (typeof define === 'function' && define.amd) {
		define([
			'epitome/epitome-collection-sync',
			'models/comment'
		], wrap);
	}
	else {
		exports.Ccomment || (exports.Ccomment = {});
		exports.Ccomment.Comments = wrap(Epitome.Collection.Sync,
			exports.Ccomment.Comment);
	}

}(this));