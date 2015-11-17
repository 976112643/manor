var MoveDivImg = function(_args){
    var that = this;
    that.div_name = _args.div_name || "";
    that.img_name = _args.img_name || "";
    that.title_name = _args.title_name || "";
    that.recommend_img_name = _args.recommend_img_name || "";
    that.focus_name = _args.focus_name || "";
    that.focus_img_name = _args.focus_img_name || "";
    that.focus_img_src = _args.focus_img_src || "";
    that.img_time = _args.img_time || 0; //����ƶ�ʱ�л����ͼƬ�ӳ�ʱ��
    that.div_detail_array = _args.div_detail_array || null; //��Ҫ����div�����λ������
    that.div_focus_array = _args.div_focus_array || null; //��Ҫ�����Ĺ��λ������
    that.content_array = _args.content_array || null; //��Ҫ�������������
    that.slip_focus_min = _args.slip_focus_min || 0; //����ƶ��߽���Сֵ
    that.slip_focus_max = _args.slip_focus_max || 0; //����ƶ��߽����ֵ
    that.div_num = _args.div_num || 0; //��������Ŀ
    that.focus_pos = _args.focus_pos || 0; //���λ��
    that.move_num = that.focus_pos; //�ƶ�������Ĭ�Ͽ�ʼ����Ϊ���λ�þ���
    that.focus_first_position = _args.focus_first_position || 0;
    that.focus_distance = _args.focus_distance || 0;
    that.focus_direction = _args.focus_direction || 0; //����ƶ�����0-ˮƽ��1-��ֱ
    that.only_focus_move = _args.only_focus_move || 0; //����ƶ�������ϣ��Ƿ��ƶ�divͼ�β�ı�־��0-ֻ�ƶ���꣬1-�ƶ�ͼ�β�
    that.size = 0;
	that.focus_last_to_one = _args.focus_last_to_one || 0;   //�����һ�����Ƿ��Ƶ���һ��  0-�ƶ��� 1-���ƶ�
	
	that.td_title = _args.td_title || "";
	that.td_status = _args.td_status || "";
	that.td_type = _args.td_type || "";
	that.td_time = _args.td_time || "";
	that.td_number = _args.td_number || "";
	that.td_title_num = _args.td_title_num || 10;
	that.from_td = _args.from_td || 0;
    
    that.$ = function(_id){
        return document.getElementById(_id);
    }
    
    that.init = function(){
        that.size = that.div_num > that.content_array.length ? that.content_array.length : that.div_num;
        that.show(0);
    }
    
    //��ʼ��ͼƬ
    that.show = function(_num){
		if(0 != that.from_td) {
			that.changeContent();
			return;
		}
        var num = 0;
        for (var i = 0; i < that.size; i++) {
            num = (i + _num) % that.content_array.length;
            that.showOne(i, num);
        }
    }
    
    that.showOne = function(_i, _num){
        if ("" != that.img_name && null != that.img_name) {
            that.$(that.img_name + _i).src = that.content_array[_num].img;
        }
        if ("" != that.title_name && null != that.title_name) {
            that.$(that.title_name + _i).innerText = that.content_array[_num].title;
        }
        if ("" != that.recommend_img_name && null != that.recommend_img_name) {
            that.$(that.recommend_img_name + _i).src = that.content_array[_num].recommendImg;
        }
    }
    
    //�ƶ�
    that.move = function(_num){
        //that.focus_pos += _num;
    	
    	
		that.focus_pos = parseInt(that.focus_pos)+parseInt(_num);
        //that.move_num += _num;
		that.move_num = parseInt(that.move_num)+parseInt(_num);
		if(0 == that.focus_last_to_one) {
			that.move_num = (that.move_num + that.content_array.length) % that.content_array.length;
		} else {
			if(that.move_num > that.content_array.length - 1) {
				that.move_num = that.content_array.length - 1;
			}
			if(that.move_num < 0) {
				that.move_num = 0;
			}
		}
        if (that.focus_pos < that.slip_focus_min) {
            that.focus_pos = that.slip_focus_min;
            if (0 != that.only_focus_move) {
				if(0 != that.from_td) {
					//alert("arguments[1]"+arguments[1]);
					if(arguments[1]!="undefined"&&arguments[1]!=""){
    					that.changeContent("channelPressUp");
    				}else{
    					that.changeContent();
    				}
					
				} else {
					that.overMove(_num);
				}
            }
        }
        else 
            if (that.focus_pos > that.slip_focus_max) {
                that.focus_pos = that.slip_focus_max;
                if (0 != that.only_focus_move) {                  
                    if (0 != that.from_td) {
                        that.changeContent();
                    }
                    else {
                        that.overMove(_num);
                    }
                }
            }
            else {
                if ("" != that.div_focus_array && null != that.div_focus_array) {
                    that.focueMoveArray();
                }
                else {
                    that.focueMove();
                }
            }
    }
    
    //ͼƬ���ƶ�
    that.overMove = function(_num){
        var num = 0;
        for (var i = 0; i < that.div_num; i++) {
            var div_obj = that.$(that.div_name + i);
            var img_obj = that.$(that.img_name + i);
            num = (that.div_num + that.focus_pos - that.move_num % that.div_num + i) % that.div_num;
            
            if (num == that.div_detail_array.length - 1 && _num > 0) {
                var num_change_img = (that.content_array.length + (that.div_num + that.move_num - that.focus_pos - 1) % that.content_array.length) % that.content_array.length;
                that.showOne(i, num_change_img);
            }
            if (0 == num && _num < 0) {
                var num_change_img = (that.content_array.length + (that.move_num - that.focus_pos) % that.content_array.length) % that.content_array.length;
                that.showOne(i, num_change_img);
            }
            div_obj.style.left = that.div_detail_array[num].left;
            div_obj.style.top = that.div_detail_array[num].top;
            div_obj.style.width = that.div_detail_array[num].width;
            div_obj.style.height = that.div_detail_array[num].height;
            div_obj.style.zIndex = that.div_detail_array[num].zIndex;
            div_obj.style.opacity = that.div_detail_array[num].opacity;
            img_obj.style.width = that.div_detail_array[num].width;
            img_obj.style.height = that.div_detail_array[num].height;
            if ("" != that.title_name && null != that.title_name) {
                that.$(that.title_name + i).style.visibility = that.div_detail_array[num].isvisible;
            }
            if ("" != that.recommend_img_name && null != that.recommend_img_name) {
                that.$(that.recommend_img_name + i).style.visibility = that.div_detail_array[num].isvisible;
            }
        }
    }
	
	//ֻ���td��������ݸ���
	that.changeContent = function(type) {
		var num = 0;
		
		if(that.content_array.length<that.div_num){
			for (var i = 0; i < that.content_array.length; i++) {
            num = (that.move_num + i - that.focus_pos + that.content_array.length) % that.content_array.length;
			if("" != that.td_number && null != that.td_number) {
				that.$(that.td_number + i).innerText = num + 1;
			}
			if("" != that.td_title && null != that.td_title) {
				var str = "";
				if(that.content_array[num].title.length > that.td_title_num) {
					str = that.content_array[num].title.substring(0, that.td_title_num) + "...";
				} else {
					str = that.content_array[num].title;
				}
				that.$(that.td_title + i).innerText = str;
			}
			if("" != that.td_type && null != that.td_type) {
				that.$(that.td_type + i).innerText = that.content_array[num].type;
			}
			if("" != that.td_time && null != that.td_time) {
				that.$(that.td_time + i).innerText = that.content_array[num].time;
			}
			if("" != that.td_status && null != that.td_status) {
				that.$(that.td_status + i).src = that.content_array[num].status;
			}
			
			//lwp ����Ƶ���б�Ϊ����ѡ�ʱ�������ͼ��
			if(that.td_title == "channel"){
				testClass(i,that.content_array[num].title);
			}
        }
		}else if(""!=type&&"channelPressUp"==type){
			
			var channelLength = (that.div_num<=9)?that.div_num+1:10;
			
			for (var i = 0; i < channelLength; i++) {
	            num = (that.move_num + i - that.focus_pos + that.content_array.length) % that.content_array.length;
				if("" != that.td_number && null != that.td_number) {
					that.$(that.td_number + i).innerText = num + 1;
				}
				if("" != that.td_title && null != that.td_title) {
					var str = "";
					if(that.content_array[num].title.length > that.td_title_num) {
						str = that.content_array[num].title.substring(0, that.td_title_num) + "...";
					} else {
						str = that.content_array[num].title;
					}
					that.$(that.td_title + i).innerText = str;
				}
				if("" != that.td_type && null != that.td_type) {
					that.$(that.td_type + i).innerText = that.content_array[num].type;
				}
				if("" != that.td_time && null != that.td_time) {
					that.$(that.td_time + i).innerText = that.content_array[num].time;
				}
				if("" != that.td_status && null != that.td_status) {
					that.$(that.td_status + i).src = that.content_array[num].status;
				}
				
				//lwp ����Ƶ���б�Ϊ����ѡ�ʱ�������ͼ��
				if(that.td_title == "channel"){
					testClass(i,that.content_array[num].title);
				}
        	}
		}else{
			for (var i = 0; i < that.div_num; i++) {
	            num = (that.move_num + i - that.focus_pos + that.content_array.length) % that.content_array.length;
				if("" != that.td_number && null != that.td_number) {
					that.$(that.td_number + i).innerText = num + 1;
				}
				if("" != that.td_title && null != that.td_title) {
					var str = "";
					if(that.content_array[num].title.length > that.td_title_num) {
						str = that.content_array[num].title.substring(0, that.td_title_num) + "...";
					} else {
						str = that.content_array[num].title;
					}
					that.$(that.td_title + i).innerText = str;
				}
				if("" != that.td_type && null != that.td_type) {
					that.$(that.td_type + i).innerText = that.content_array[num].type;
				}
				if("" != that.td_time && null != that.td_time) {
					that.$(that.td_time + i).innerText = that.content_array[num].time;
				}
				if("" != that.td_status && null != that.td_status) {
					that.$(that.td_status + i).src = that.content_array[num].status;
				}
				
				//lwp ����Ƶ���б�Ϊ����ѡ�ʱ�������ͼ��
				if(that.td_title == "channel"){
					testClass(i,that.content_array[num].title);
				}
        	}
		}
		
        
	}
    
    //����ƶ�,�ܶ�����ֵ
    that.focueMoveArray = function(){
        var focus_obj = that.$(that.focus_name);
        focus_obj.style.left = that.div_focus_array[that.focus_pos].left;
        focus_obj.style.top = that.div_focus_array[that.focus_pos].top;
        if ("" != that.focus_img_src || null != that.focus_img_src) {
            var focus_img_obj = that.$(that.focus_img_name);
            focus_obj.style.width = that.div_focus_array[that.focus_pos].width;
            focus_obj.style.height = that.div_focus_array[that.focus_pos].height;
            focus_img_obj.style.width = that.div_focus_array[that.focus_pos].width;
            focus_img_obj.style.height = that.div_focus_array[that.focus_pos].height;
            setTimeout(function(){
                focus_img_obj.src = that.div_focus_array[that.focus_pos].img_src;
            }, that.img_time);
        }
    }
    
    //����ƶ�����һ
    that.focueMove = function(){
        var focus_obj = that.$(that.focus_name);
        if (0 == that.focus_direction) {
            focus_obj.style.left = that.focus_first_position + (that.focus_pos - that.slip_focus_min) * that.focus_distance + "px";
        }
        else {
            focus_obj.style.top = that.focus_first_position + (that.focus_pos - that.slip_focus_min) * that.focus_distance + "px";
        }
    }
    
    that.focusInit = function(){
        if ("" != that.div_focus_array && null != that.div_focus_array) {
            that.focueMoveArray();
        }
        else {
            that.focueMove();
        }
    }
}

var ShowFocusProp = function(_prop_length, _total_number, _pos, _focus, _foucs_img, _direction){
    var that = this;
	that.prop_length = _prop_length || 10;
	that.total_number = _total_number || 10;
	that.pos = _pos || 0;
	that.focus = _focus || "";
	that.focus_img = _foucs_img || "";
	that.direction = _direction || 0;    //0-��ֱ�� 1-ˮƽ
	that.size = 0;
	that.prop_show_time = 0;
	
	that.$ = function(_id) {
		return document.getElementById(_id);
	}
	
	that.init = function() {
		that.size = that.prop_length / that.total_number;
		var focus_obj = that.$(that.focus);
		var focus_img_obj = that.$(that.focus_img);
		if(0 == that.direction) {
			focus_obj.style.top = -2 + that.pos * that.size + "px";
			focus_img_obj.style.height = that.size + "px";
		} else {
			focus_obj.style.left = -2 + that.pos * that.size + "px";
			focus_img_obj.style.width = that.size + "px";
		}
	}
	 
    that.show = function(_pos){
		var focus_obj = that.$(that.focus);
		if(0 == that.direction) {
			focus_obj.style.top = -2 + _pos * that.size + "px";
		} else {
			focus_obj.style.left = -2 + _pos * that.size + "px";
		}
        clearTimeout(that.prop_show_time);
        focus_obj.style.opacity = 1;
//        that.prop_show_time = setTimeout(function() {
//			focus_obj.style.opacity = 0;
//		}, 1000);
    }
}