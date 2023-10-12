var GUEST_TOKEN;

$(document).ready(function(e) {
	
	if (isPasoA) {
		GUEST_TOKEN = getGuestToken();
		loadSections();
		
		if(_capturaIdSesionWeb != '0'){
			mostrarLoadCapturaReserva();
		}
		
		$("#divHeaderSecciones").bind("tap", function(evt){
			evt.preventDefault();
			showSections();		
		});
	
		$("#divHeaderBloques").bind("tap", function(evt){
			evt.preventDefault();
			showBlocks();		
		});
		
		$("#bContinuar").off("click touchend").on("click touchend", function(evt){
			evt.preventDefault();
			validateSelection(true, function(r,e){});		
		});
		
		if(_isOnlineEvent){
			$("#divBloques").hide();
			$("#divHeaderBloques").hide();
		}

		//$("body").addClass("loaded");		
	} 
	
});

function loadSections(){
	
	cleanSectionSelection();
	
	var url = '/v2/sections/event/' + _idevento;
	PrepareWait();
	$.ajax({
		url: API_URL + url,
		cache: false,
		type: 'GET',
		tryCount : 0,
		retryLimit : 3,
		headers: { 'Authorization': 'Bearer ' + GUEST_TOKEN },
		success: function(d){
			CancelLoading();
			$("#divSecciones").hide();
			$("#mensajeSuccess").hide();
			$("#mensajeValidacion").hide();

			$("#divHeaderSecciones").hide();
			$("#divHeaderBloques").hide();
			$("#divHeaderPrecios").hide();

			$("#divSecciones").html("");
			$(d).each(function(i, e) {

				var dt = $($("#templateSeccion").html());
				$(dt).attr("data-sectionid",e.sectionID);

				$(dt).find(".titulo").text(e.name);
				$(dt).find(".precio").text(e.lowestPriceAvailable > 0  
											? (e.availablePricesQuantity>1?"Desde " : "") + e.lowestPriceAvailable.formatMoney(_currencyMask, _decimalAmount)
											: "");

				//var isSoldOut = true;
				if(!e.isSoldOut){
					$(dt).find(".agotado").hide();
					$(dt).find(".precio").show();
					$(dt).bind("tap", function(evt){
						evt.preventDefault();
						setSelectedSection(this, e);
						cleanBlockSelection();
						loadBlocks(e);
					});
				}else{
					$(dt).addClass("agotado");
					$(dt).find(".precio").hide();
					$(dt).find(".agotado").show();
				}

				$(dt).show();
				$("#divSecciones").append(dt);

			});						
			
			if(_isOnlineEvent && $("#divSecciones>.seccion").length==1){
				$("#divSecciones>.seccion").click();
				$("#divHeaderSecciones").hide();
				$("#divHeaderPrecios").html($("#divSecciones>.seccion>.titulo").html() + "<br>Selecciona tus boletos:").show();
			}else{
				$("#divHeaderSecciones").show();
				showSections();
			}

			
		},
		error: function(e){
			console.log(url + " error:" + JSON.stringify(e));
			if (e.status == 403) {
				refreshGuest(this);
			}
		}
	});	
	
	
}
// Carga la informacion de los bloques para mostrarlos
// para el Paso1c, llena la informacion de los bloques con el precio, del lado derecho
function loadBlocks(section){
	
	var url = '/v2/blocks/event/' + _idevento + "/" + section.sectionID;
	PrepareWait();		
	$.ajax({
		url: API_URL + url,
		cache: false,
		type: 'GET',
		tryCount : 0,
		retryLimit : 3,
		headers: { 'Authorization': 'Bearer ' + GUEST_TOKEN },
		success: function(d){

			CancelLoading();
			$(_bloque).html("");

			var cantidadBloques = 0;
			var bloquesAgotados = 0;
			$(d).each(function(i, e) {

				if(e.blockId > 0)
					cantidadBloques++;

				if (!isPasoA) { // para los maximos en la Paso1c
					var maximosSeleccionados = $(
						"<div style='width:100%;'>" +
							"<input id='tTitulo_" + $(e).attr("idType") + "' type='text' value='" + $(e).attr("typeDescription") + "'>" +
							"<input id='tPermitidos_" + $(e).attr("idType") + "' tyte='text' value='" + $(e).attr("allowedQuantities") + "'>" +
						"</div>"
					);

					$("#preciosOcultos").append(maximosSeleccionados);
				}

				var dt = $($("#templateBloque").html()); //plantilla del bloque que existe en ambas paginas
				var msg = "";
				if(e.blockId > 0 && e.remainingSeats == 0){
					bloquesAgotados++;
					$(dt).addClass("agotado");
					msg = '<b class="agotado">AGOTADO</b>';

				} else if(e.remainingSeats > 0 && e.reminaingSeats <=50){
					msg = '<b class="ultimos">ULTIMOS LUGARES</b>';
				}

				$(dt).attr({"data-blockid":e.blockId, "remainingSeats":e.remainingSeats});	
				$(dt).find(".titulo").text(e.name);
				$(dt).find(".mensaje").html(msg);

				if(e.remainingSeats > 0 || e.blockId == 0){
					$(dt).bind("tap", function(evt){
						section.remainingSeats = e.remainingSeats;
						setSelectedBlock(dt, e);
						loadPrices(section, null);
					});
				}

				if(section.isGeneral && e.blockId == 0){
					section.remainingSeats = e.remainingSeats;
					setSelectedBlock(dt, e);
					loadPrices(section, null);
				}
				
				$(dt).show();
				$(_bloque).append(dt);

			});	

			if (isPasoA) {
				console.log("cantidadBloques="+cantidadBloques+" agotados="+bloquesAgotados);
				if(cantidadBloques==bloquesAgotados){
					$("[data-blockid='0']").hide();
				}

				$("#divBloques").hide();
				$("#divHeaderBloques").hide();
				if(!_isOnlineEvent && !section.isGeneral){
					showBlocks();
					$("#divBloques").show();
					$("#divHeaderBloques").show();
				}
			}
		},
		error: function(e){
			console.log(url + " error:" + JSON.stringify(e));
			if (e.status == 403) {
				refreshGuest(this);
			}
		}
	});
		
}

function loadPrices(section, callback){

	var url = '/v2/websales/prices/' + _idevento + "/" + section.sectionID;
	PrepareWait();
	$.ajax({
		url: API_URL + url,
		cache: false,
		type: 'GET',
		tryCount : 0,
		retryLimit : 3,
		headers: { 'Authorization': 'Bearer ' + GUEST_TOKEN },

		success: function(d){  // aca tambien se llena el bloque derecho de los precion en 1c

			CancelLoading();
			if (isPasoA) {
				$("#divPrecios").html("");

				$(d).each(function(i, e) {
					
					
					e.remainingSeats = section.remainingSeats;
					$("#divPrecios").append(getAsientosData(e));
					$(e).attr("allowedQuantities", "0," + $(e).attr("allowedQuantities"))				
				});	 // crea el bloque de precio y lo agrega al bloque base

				$('[data-toggle="tooltip"]').tooltip({
					html: true	
				}); 
				showPrices();

			} else {
				
				prices = d; // guardar los precios para la animacion
				var sectionID;
				var dt;
				var data_content;

				if ($(_bloque).children().length == 0) {
					$(d).each(function(i, e) {
						sectionID = $(e).attr("idSection").toString();

						if($("#PreciosCompra").children("#" + sectionID).length == 0){
							dt = $($("#templateBloque").html()); //plantilla del bloque que existe en ambas paginas

							$(dt).attr('id', sectionID);
							$(dt).find("#sectionID").attr('id', sectionID);	
							$(dt).find(".col_i-Desplegada_o-SmartPhone").text(e.sectionDescription);
							$(dt).find("#typeDesc").text(e.typeDescription);
							$(dt).find("strong").html(e.idPromotion > 0 && !e.isRestrictedCapacity ? "&#128274;" : e.total.formatMoney(_currencyMask, _decimalAmount));	

							data_content = $(dt).find("div[data-toggle='popover']").attr("data-content"); 
							$(dt).find("div[data-toggle='popover']").attr("data-content", 
								e.idPromotion > 0 && !e.isRestrictedCapacity ? 
									"Necesita c\xF3digo de promoci\xF3n" :
									data_content.replace("[price]", (e.idPromotion != 0 ? "&#128274;" : e.price.formatMoney(_currencyMask, _decimalAmount)) )
												.replace("[charge]", e.charge.formatMoney(_currencyMask, _decimalAmount)));

							InsertMousePrecios($(dt)); // se llama en Paso1c
							ToolTipPopover();

							$(_bloque).append(dt);
							$(dt).show();

						} else {
							dt = $($("#templateMontos").html());

							$(dt).find("#MontoDesc").text(e.typeDescription);	
							$(dt).find("strong").html(e.idPromotion > 0 && !e.isRestrictedCapacity ? "&#128274;" : e.total.formatMoney(_currencyMask, _decimalAmount));						

							data_content = $(dt).find("div[data-toggle='popover']").attr("data-content"); 
							$(dt).find("div[data-toggle='popover']").attr("data-content", 
								e.idPromotion > 0 && !e.isRestrictedCapacity ? 
									"Necesita c\xF3digo de promoci\xF3n" :
									data_content.replace("[price]", (e.idPromotion != 0 ? "&#128274;" : e.price.formatMoney(_currencyMask, _decimalAmount)) )
												.replace("[charge]", e.charge.formatMoney(_currencyMask, _decimalAmount)));

							$("#PreciosCompra").children("#" + sectionID).find('#precios').append(dt);
						}			
					});					
				}
				callback();
			}

			$("#divHeaderPrecios").show();
		},

		error: function(e){
			console.log(url + " error:" + JSON.stringify(e));
			if (e.status == 403) {
				refreshGuest(this);
			}
		}
	});
}

function CreateQtys (aQuantities){		
	return $.merge(["0"], $.grep(aQuantities.split(","), function(v) { return v!= 0; }));
}

function mostrarLoadCapturaReserva(){
	$("#copete").hide();
	$("#MenuPrincipal").hide();
	$("#divLoadCapturaReserva").show()
} 

function ocultarLoadCapturaReserva(){
	$("#divLoadCapturaReserva").hide()
	$("#copete").show();
	$("#MenuPrincipal").show();
}

// Crea el bloque para los precios
// se manda a llamar desde loadPrices(section)
function getAsientosData(e){
	
	var selectbutton;
	var dt = $($("#templatePrecio").html()); // esta etiqueta debe existir en ambas paginas, igual.	
	$(dt).find(".precio.etiqueta").text(e.typeDescription);
	
	var precioValor =$(dt).find(".precio.valor");
	precioValor.text(e.total.formatMoney(_currencyMask, _decimalAmount));
	precioValor.attr("data-priceid", e.idPrice);
	precioValor.prop("title","Importe: " + e.price.formatMoney(_currencyMask, _decimalAmount) + "<br> Cargos: " + e.charge.formatMoney(_currencyMask, _decimalAmount));
	
	var isPreferencial = false;
	$(dt).show();
		
	var allowedQtys = CreateQtys(e.allowedQuantities);
	
	var hasPromoAdded = $("#divBoletosSeleccionados > .precio[data-promotionid = '"+e.idPromotion+"']").length > 0;	
					
	var sb_opts = {
		'type': 'horizontal', 
		'customSteps': allowedQtys };
	
	if (!isPasoA) { 
		$("#preciosOcultos").empty();// limpiar los precios ocultos
		
		precioValor.attr('data-price-db', e.total);
		precioValor.attr('data-original-title', 'Importe: ' + e.price.formatMoney(_currencyMask, _decimalAmount));
		precioValor.attr('Cargos', e.charge.formatMoney(_currencyMask, _decimalAmount));
		
		$(e).each(function(i, element) { 
			var maximosSeleccionados = $(
					"<div style='width:100%;'>" +
						"<input id='tTitulo_" + $(element).attr("idType") + "' type='text' value='" + $(element).attr("typeDescription") + "'>" +
						"<input id='tPermitidos_" + $(element).attr("idType") + "' type='text' value='" + $(element).attr("allowedQuantities") + "'></div>"
				);
			$("#preciosOcultos").append(maximosSeleccionados); 
		});		
		
		isPreferencial = $(jsonAsientos).attr("rows").length > 0;
	}
	
	if (isPreferencial) { 
		
		/* Boton para seleccion cuando es por Preferencial */	
		selectbutton = $("<input id='btnComprar" + e.idPrice + "' " +
						  "type='button' class='goButton' " + 
						  "data-priceid='" + e.idPrice + "' " + 						  
						  "data-typeId='" + e.idType + "' " + 
						  "data-promotionid='" + e.idPromotion + "' " + 
						  "data-isrestrictedcapacity='" + e.isRestrictedCapacity + "' " + 
						  "data-allowedqtys='" + allowedQtys + "' " + 
						  "data-options='" + JSON.stringify(sb_opts) + "' " +
						  "select-seat='0' " +
						  "value='Seleccionar' />");
		
		$(selectbutton).on("click", function () {
			// var idPrecioSeleccionado = $("#mensajeValidacionSoloCodigo").hide(); <-- no se usa?
			if(parseInt($(this).attr("data-promotionid")) == 0) // es general
				CompraroBoleto(this);
			else{
				$(this).attr("select-seat", $(this).data("allowedqtys").split(",")[1]);
				
				validateSelection(false, function(r, e) { 
					var btn = $("#btnComprar"+ e.priceId);
					$(btn).attr("select-seat", "0");
					if (r == true) {						
						if ($(btn).val() =="Validar") {
							$(".codigopromo_container > .codigo[data-promotionid='" + e.promotionId + "']").hide();//attr('readonly', true);
							$(btn).val("Seleccionar");
							$(".precio.valor[data-priceid='"+ e.priceId+"']").show();
							
							ShowSuccessCode(e.code);
						} else
							CompraroBoleto($("#btnComprar"+ e.priceId));
					} 
				}); // se llama paso1_comun, este valida tambien el codigo de promocion
			}
		});
	
	} else {
		
		/* spinner cantidad de boletos en General */		
		var sb_max = e.remainingSeats;			
		if(sb_max > 10)
			sb_max = 10;
		else{

			var sbm = "";
			if(sb_max==0){
				sbm = "Localidades agotadas";
			}else if(sb_max==1){
				//sbm = "Útimo lugar disponible";
			}else{
				//sbm = "&#161;Solo quedan "+sb_max+" lugares&#33";
			}

			if(sbm!=''){
				$("#mensajeInfo")
					.html(sbm)
					.show();
			}				
		}
			
		var spinbox = $("<input type='text' class='spinbox SoloEnteros' select-seat='0' " +
						   "id='btnComprar" + e.idPrice + "' " +
						   "data-priceid='" + e.idPrice + "' " + 						  
						   "data-typeId='" + e.idType + "' " + 
						   "data-promotionid='" + e.idPromotion + "' " + 
						   "data-isrestrictedcapacity='" + e.isRestrictedCapacity + "' " + 
						   "data-allowedqtys='" + allowedQtys + "' " + 
						   "data-options='" + JSON.stringify(sb_opts) + "' " +
						   "value='0' min='0' max='" + sb_max + "'/>");
		
		$(spinbox).keypress(function(evt){
			 var charCode = (evt.which) ? evt.which : event.keyCode
			 return ((charCode >= 48 && charCode <= 57) || charCode == 8);
		});				
		
		$(dt).find(".spinbox_container").append(spinbox);
		
		spinbox.TouchSpin({
			value: 0,
			min: 0,
			max: sb_max,
			mousewheel: false,
			data_array: allowedQtys,
			data_arrayIndex: 0
		});
		
		if (!isPasoA) {
			$("#divSeccion").children('span')[0].innerHTML = "General";
            $("#divFila").children('span')[0].innerHTML = "General";
            $("#divAsiento").children('span')[0].innerHTML = "General";
		}
		
		selectbutton = $("<input id='btnContinuar" + e.idPrice + "' "+
						   "type='button' value='Continuar' "+
						   "class='goButton'/>"); //"data-dismiss='modal' <= para cerrar automaticamente el modal	
		
		$(selectbutton).on("click", function () {
			var tosave = $.inArray($(this).val(), ["Continuar", "Seleccionar"]) > -1;
			var btn = $(this).parent().find(".spinbox");
			
			$(btn).attr("select-seat", $(btn).is(":visible") ? $(btn).val() : $(btn).data("allowedqtys").split(",")[1]);
			
			validateSelection(tosave, function(r,e){
				var btn = $("#btnContinuar"+ e.priceId);
				
				$(btn).parent().find(".spinbox").attr("select-seat", "0");
				$(btn).parent().find(".spinbox").val("0");
				if (r == true) {						
					if ($(btn).val() =="Validar") {
						$(".codigopromo_container > .codigo[data-promotionid='" + e.promotionId + "']").hide();//.attr('readonly', true);
						if ($(btn).parent().find(".bootstrap-touchspin").length > 0 && !isPasoA)
							$(btn).val("Continuar");
						else {
							$(btn).val("Seleccionar");
							$(btn).hide();
						}
						$(".precio.valor[data-priceid='"+ e.priceId+"']").show();
						$("#btnComprar"+ e.priceId + ".spinbox").parent().show();
						$(btn).attr("select-seat", "0");

						ShowSuccessCode(e.code);
					}
				}

			}); // se llama paso1_comun, este valida tambien el codigo de promocion
		});		
		
		if(e.idPromotion>0 && !e.isRestrictedCapacity && !hasPromoAdded) {
			$(spinbox).parent().hide();
		}
			
		$("#CargandoAsientos").empty();
	}
		
	$(dt).find(".spinbox_container").append(selectbutton);
	
	/* codigo de promocion */
	if(e.idPromotion>0 && !e.isRestrictedCapacity){
		
		var placeHolder = (_idevento == 24018 ? "Folio boleto general..." : 'Codigo de promoci\xF3n..');
							
		//var tcodigopromo = "<input type='text' class='codigo' placeholder='Codigo de promoci&oacute;n...' data-promotionid='" + e.idPromotion + "'/>";	
		var tcodigopromo = document.createElement('input');
		tcodigopromo.type = 'text';
		tcodigopromo.className = 'codigo';
		tcodigopromo.setAttribute('placeholder', placeHolder);
		tcodigopromo.setAttribute('data-promotionid', e.idPromotion);
		tcodigopromo.setAttribute('data-typeid', e.idType);
		tcodigopromo.setAttribute('data-isrestrictedcapacity', e.isRestrictedCapacity);
		
		if (!hasPromoAdded) {
			$(precioValor).hide();		
			$(selectbutton).val("Validar");
		} else {
			var promoCode = $("#divBoletosSeleccionados > .precio[data-promotionid != '0']:first").data("code");
			tcodigopromo.style.display = 'none';
			tcodigopromo.value = promoCode;
		}

		$(dt).find(".codigopromo_container").append(tcodigopromo);
	}
	
	if (isPasoA && $(selectbutton).val()  !== "Validar") { // en el pasoA se ocultaran los botones de seleccion, solo el de "Validar" debe de ser mostrado
		$(selectbutton).hide();
	}
	
	return dt;
}

function ShowSuccessCode(code) {
	$("#mensajeSuccess")
		.html("Se agreg&#243; correctamente el c&#243;digo: " + code)
		.show();
}

function getSelectionData(){
	var sectionid;
	var blockid;
	var selector = "*[id^='btnComprar']";
	var asientos = "";
	
	if (isPasoA) {
		var section_sel = $(".seccion.contenedor.seleccionado")
		sectionid = $(section_sel).length == 1 ? $(section_sel).data("sectionid") : -1;
	
		var block_sel = $(".bloque.contenedor.seleccionado")
		blockid = $(block_sel).length == 1 ? $(block_sel).data("blockid") : -1;
		
	} else { // saca los datos del modal y el div de asientos
		sectionid = $('#divSVGAsientos').attr('section-id');
		blockid = $("#divSVGAsientos").attr("blockid");
		
		if ($("#modalAsientos").find("#divSeccion > span.precio.valor").text() == "General") {
			blockid = 0;
		}
		
		if ($("#modalAsientos").is(":hidden")) // clic con boton continuar principal
			selector = "#divBoletosSeleccionados > .precio";
			
		asientos = GeneraCadenaAsientos();	
	}		
	
	var prices_sel = new Array();
	$(selector).each(function(i, e) {
		var amt = 0;
		var priceid = $(e).data("priceid");
		var typeid = $(e).data("typeid");
		
		amt = jQuery.isNumeric($(e).val()) ? eval($(e).val()) : 0;
		amt = (amt > 0 ? amt : parseInt($(e).attr("select-seat")));
		
		var idx = prices_sel.findIndex(function(p) {
										return p.priceId === priceid && p.typeId === typeid; });
		
		if (idx > -1)
			prices_sel[idx].amount = prices_sel[idx].amount + amt;
		else {
			
			if (!isPasoA) {				
				$(prices)
					.filter(function(i,p){ return p.idSection == parseInt(idSection); })
					.each(function(x,p) {
						prices_sel.push({
							priceId: p.idPrice,
							typeId: p.idType,
							amount: (p.idPrice == priceid && p.idType == typeid ? amt : 0),
							allowedqtys: p.allowedQuantities,
							promotionId: p.idPromotion,
							promotionCode: p.idPromotion > 0 && p.isRestrictedCapacity ? 'REST_CAP' : p.idPromotion > 0 ? $(".codigo[data-promotionid='" + p.idPromotion + "'][data-typeid='" + p.idType + "']:first").val() : ""
						});
					});
			} else {
				var promotionid = $(e).data("promotionid");
				var isRestrictedCapacity = $(e).data("isrestrictedcapacity") == true;
				var typeid = $(e).data("typeid");
				prices_sel.push({
					priceId: priceid,
					typeId: typeid,
					amount: amt,
					allowedqtys: $(e).data("allowedqtys"),
					promotionId: promotionid,
					promotionCode: promotionid > 0 && isRestrictedCapacity? 'REST_CAP' : promotionid > 0 ? $(".codigo[data-promotionid='" + promotionid + "'][data-typeid='" + typeid + "']:first").val() : ""
				});
			}
		}		
    });	
	
	return {
		eventId: _idevento,
		sectionId: sectionid,
		blockId: blockid,
		prices: prices_sel,
		selectedSeats: asientos
	};	
	
}

function checExistsPrice(p) {
  return p.priceId === 132827 && p.typeId ===3;
}

function cleanSectionSelection(){
	$("#spZonaSeleccionada").html("");
	$(".seccion.contenedor").removeClass("seleccionado");	
}

function cleanBlockSelection(){
	$("#spBloqueSeleccionado").html("");
	$(".bloque.contenedor").removeClass("seleccionado");
}

function setSelectedSection(d,e){
	cleanSectionSelection();
	$("#spZonaSeleccionada").html("&gt;&nbsp;"+e.name);
	$(d).addClass("seleccionado");
}

function setSelectedBlock(d,e){
	if (isPasoA) {
		cleanBlockSelection();
		$("#spBloqueSeleccionado").html("&gt;&nbsp;"+e.name);
		$(d).addClass("seleccionado");
		$("#mensajeValidacion").hide();
	}	
}

function showSections(){
	$("#mensajeSuccess, #mensajeValidacion, #mensajeInfo").hide();
	$("#divSecciones").show();
	$("#divSecciones .contenedor").show();
	$(_bloque).hide();	
	$("#divPrecios").hide();	
}

function showBlocks(){
	$("#mensajeSuccess, #mensajeValidacion, #mensajeInfo").hide();
	$("#divSecciones").hide();
	$(_bloque).show();	
	$(_bloque).find(".contenedor").show();
	$("#divPrecios").hide();	
}

function showPrices(){
	if (isPasoA) {
		$("#divSecciones").hide();
		$(_bloque).hide();	
		$("#divPrecios").show();
		$("#divPrecios .contenedor").show();
	}
}

