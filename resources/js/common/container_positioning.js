/**
 * Rinkinys įrankių, skirtų darbui su konteinerių elementų pozicijomis.
 * 
 * Tam, kad veiktų šis mechanizmas reikia:
 * 	1) pozicionuojami elementai turi būti įdėti į konteinerius su klase "element_container" ir id "element_container_{elemento-id}"
 * 	2) turi būti globalus js kintamasis, saugantis kiek yra poziciuonuojamų elementų "container_element_count". (TODO: apskaičiuoti automatiškai inicializuojant pozicionavimo mechanizmą)
 * 
 * @date 	2009-09-24
 * @author  kran
 * @since 	0.7
 */

var min_position=0;
var autoSave = false;

function initContainerPositioningManager(dragable, oneElement, autoSaveItems){
	autoSave = autoSaveItems;
	
	if (dragable && (container_element_count > 1)){
		$(".element_container").draggable({start:start_drag, stop:stop_drag, drag:drag_drag, axis:'y', delay:20, containment:false});
	}
	initControls();
	
	$("#module_content").ajaxStart(function(){$('#module_content').addClass("ajax_overlay");}).ajaxStop(function(){$('#module_content').removeClass("ajax_overlay");});
	
	if ($(".element_container:first").length > 0){
		var id = $(".element_container:first")[0].id.replace('element_container_', '');
		min_position = $("#element_position_"+id).val();
	}
	
	$("#entity_form").submit(function(){
		fix_positions();
		return true;
	});
}

function initControls(){
	$(".element_up_a").click(function(){elementUp(this.id.replace("element_up_a_", ""));return false;});
	$(".element_down_a").click(function(){elementDown(this.id.replace("element_down_a_", ""));return false;});
	
	$(".element_container:first .element_up_a img").addClass("inactive");
	$(".element_container:first .element_up_a").on('click', function(){return false;});
	$(".element_container:last .element_down_a img").addClass("inactive");
	$(".element_container:last .element_down_a").on('click', function(){return false;});
}

function updateControls(){
	$(".element_container .element_up_a img").removeClass("inactive");
	$(".element_container:first .element_up_a img").addClass("inactive");
	$(".element_container .element_down_a img").removeClass("inactive");
	$(".element_container:last .element_down_a img").addClass("inactive");
}

function elementUp(id){
	if ($(".element_container:first")[0].id=="element_container_"+id){
		return false;
	}
	elementSetPosition(id, 1);
}

function elementDown(id){
	if ($(".element_container:last")[0].id=="element_container_"+id){
		return false;
	}
	elementSetPosition(id, -1);
}

function elementSetPosition(id, step){
	var currElement = $("#element_container_"+id)
	if (step>0){
		insertBefore(currElement[0], currElement.prev()[0]);
	}else{
		insertAfter(currElement[0], currElement.next()[0]);
	}
}

function insertBefore(toInsertElement, whereInsertElement){
	$(whereInsertElement).before(toInsertElement);
	return afterInsertion(toInsertElement, whereInsertElement, -1);
}

function insertAfter(toInsertElement, whereInsertElement){
	$(whereInsertElement).after(toInsertElement);
	return afterInsertion(toInsertElement, whereInsertElement, 1);
}

function afterInsertion(insertedElement, positionElement, change){
	$(insertedElement).removeClass('dragging').removeAttr('style');
	var inserted_element_id = insertedElement.id.replace('element_container_', '');
	var position_element_id = positionElement.id.replace('element_container_', '');
	var new_pos = parseInt($("#element_position_" + position_element_id).val()) + change;
	$("#element_position_" + inserted_element_id).val(new_pos);
	editedElement(inserted_element_id);
	$("#element_position_" + position_element_id).val(new_pos-change);
	editedElement(position_element_id);
	
	updateControls();
	return true;
}

function editedElement(id){
	$("#edited_element_"+id).val(1);
}

//start of dragging functions
function start_drag(event, ui){
	$(this).addClass('dragging');
}

function drag_drag(event, ui){
	var element = this;
	var top = $(this).position().top;
	var bottom = top + $(this).height();
	var ia_found = false;
	$(".element_container").each(function(){
		var el_top = $(this).position().top;
		var el_bottom = el_top + $(this).height();
		if ((top>el_top) && (top<el_bottom)){
			$(".element_container").removeClass('insert_after');
			$(this).addClass("insert_after");
			ia_found = true;
			return;
		}
	}); 
	if (ia_found){
		$(".element_container").removeClass('insert_before');
		$(".element_container.insert_after:not(.dragging)").next().addClass('insert_before');
	}else{
		var top_element_top = $(".element_container:first").position().top;
		var bottom_element_bottom = $(".element_container:last").position().top + $(".element_container:last").height();
		if (top < top_element_top){
			$(".element_container").removeClass('insert_before');
			$(".element_container:first").addClass('insert_before');
		}else if (top > bottom_element_bottom){
			$(".element_container").removeClass('insert_after');
			$(".element_container:last").addClass('insert_after');
		}
	}
}

function stop_drag(event, ui){
	var element = this;
	var top = $(this).position().top;
	var bottom = top + $(this).height();
	var position_found = false;
	$(".element_container").each(function(){
		var el_top = $(this).position().top;
		var el_bottom = el_top + $(this).height();
		if ((top>el_top) && (top<el_bottom)){
			$(this).after(element);
			position_found = true;
			return;
		}
	}); 
	if (!position_found){
		var top_element_top = $(".element_container:first").position().top;
		var bottom_element_bottom = $(".element_container:last").position().top + $(".element_container:last").height();
		if (top < top_element_top){
			$(".element_container:first").before(element);
		}else if (top > bottom_element_bottom){
			$(".element_container:last").after(element);
		}
	}
	$(element).css('top', '');
	fix_positions();
	
	if (autoSave){
		var url = 'position';
		var params =  new Object;
		params['state'] = 'position_items';
		$(".element_container").each(function(){
			var id= this.id.replace('element_container_', '');
			if ($("#edited_element_"+id).val()==1){
				params[id] = $("#element_position_"+id).val();
			}	
		});
		$.post(url, params);
	}
	$(element).removeClass('dragging');
	$(".element_container").removeClass("insert_before").removeClass("insert_after");
	updateControls();
}
//end of dragging functions

function fix_positions(){
	var i = min_position;
	$(".element_container").each(function(){
		var id = this.id.replace('element_container_', '');
		$("#element_position_" + id).val(i);
		editedElement(id);
		i++;
	});
	return true;
}