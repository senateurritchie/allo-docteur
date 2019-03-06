function MarkerClusterer(map,opt_markers,opt_options){this.extend(MarkerClusterer,google.maps.OverlayView);this.map_=map;this.markers_=[];this.clusters_=[];this.sizes=[53,56,66,78,90];this.styles_=[];this.ready_=false;var options=opt_options||{};this.gridSize_=options['gridSize']||60;this.minClusterSize_=options['minimumClusterSize']||2;this.maxZoom_=options['maxZoom']||null;this.styles_=options['styles']||[];this.imagePath_=options['imagePath']||this.MARKER_CLUSTER_IMAGE_PATH_;this.imageExtension_=options['imageExtension']||this.MARKER_CLUSTER_IMAGE_EXTENSION_;this.zoomOnClick_=true;if(options['zoomOnClick']!=undefined){this.zoomOnClick_=options['zoomOnClick'];}
this.averageCenter_=false;if(options['averageCenter']!=undefined){this.averageCenter_=options['averageCenter'];}
this.setupStyles_();this.setMap(map);this.prevZoom_=this.map_.getZoom();var that=this;google.maps.event.addListener(this.map_,'zoom_changed',function(){var zoom=that.map_.getZoom();if(that.prevZoom_!=zoom){that.prevZoom_=zoom;that.resetViewport();}});google.maps.event.addListener(this.map_,'idle',function(){that.redraw();});if(opt_markers&&opt_markers.length){this.addMarkers(opt_markers,false);}}
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_='https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/images/m';MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_='png';MarkerClusterer.prototype.extend=function(obj1,obj2){return(function(object){for(var property in object.prototype){this.prototype[property]=object.prototype[property];}
return this;}).apply(obj1,[obj2]);};MarkerClusterer.prototype.onAdd=function(){this.setReady_(true);};MarkerClusterer.prototype.draw=function(){};MarkerClusterer.prototype.setupStyles_=function(){if(this.styles_.length){return;}
for(var i=0,size;size=this.sizes[i];i++){this.styles_.push({url:this.imagePath_+(i+1)+'.'+this.imageExtension_,height:size,width:size});}};MarkerClusterer.prototype.fitMapToMarkers=function(){var markers=this.getMarkers();var bounds=new google.maps.LatLngBounds();for(var i=0,marker;marker=markers[i];i++){bounds.extend(marker.getPosition());}
this.map_.fitBounds(bounds);};MarkerClusterer.prototype.setStyles=function(styles){this.styles_=styles;};MarkerClusterer.prototype.getStyles=function(){return this.styles_;};MarkerClusterer.prototype.isZoomOnClick=function(){return this.zoomOnClick_;};MarkerClusterer.prototype.isAverageCenter=function(){return this.averageCenter_;};MarkerClusterer.prototype.getMarkers=function(){return this.markers_;};MarkerClusterer.prototype.getTotalMarkers=function(){return this.markers_.length;};MarkerClusterer.prototype.setMaxZoom=function(maxZoom){this.maxZoom_=maxZoom;};MarkerClusterer.prototype.getMaxZoom=function(){return this.maxZoom_;};MarkerClusterer.prototype.calculator_=function(markers,numStyles){var index=0;var count=markers.length;var dv=count;while(dv!==0){dv=parseInt(dv/10,10);index++;}
index=Math.min(index,numStyles);return{text:count,index:index};};MarkerClusterer.prototype.setCalculator=function(calculator){this.calculator_=calculator;};MarkerClusterer.prototype.getCalculator=function(){return this.calculator_;};MarkerClusterer.prototype.addMarkers=function(markers,opt_nodraw){for(var i=0,marker;marker=markers[i];i++){this.pushMarkerTo_(marker);}
if(!opt_nodraw){this.redraw();}};MarkerClusterer.prototype.pushMarkerTo_=function(marker){marker.isAdded=false;if(marker['draggable']){var that=this;google.maps.event.addListener(marker,'dragend',function(){marker.isAdded=false;that.repaint();});}
this.markers_.push(marker);};MarkerClusterer.prototype.addMarker=function(marker,opt_nodraw){this.pushMarkerTo_(marker);if(!opt_nodraw){this.redraw();}};MarkerClusterer.prototype.removeMarker_=function(marker){var index=-1;if(this.markers_.indexOf){index=this.markers_.indexOf(marker);}else{for(var i=0,m;m=this.markers_[i];i++){if(m==marker){index=i;break;}}}
if(index==-1){return false;}
marker.setMap(null);this.markers_.splice(index,1);return true;};MarkerClusterer.prototype.removeMarker=function(marker,opt_nodraw){var removed=this.removeMarker_(marker);if(!opt_nodraw&&removed){this.resetViewport();this.redraw();return true;}else{return false;}};MarkerClusterer.prototype.removeMarkers=function(markers,opt_nodraw){var removed=false;for(var i=0,marker;marker=markers[i];i++){var r=this.removeMarker_(marker);removed=removed||r;}
if(!opt_nodraw&&removed){this.resetViewport();this.redraw();return true;}};MarkerClusterer.prototype.setReady_=function(ready){if(!this.ready_){this.ready_=ready;this.createClusters_();}};MarkerClusterer.prototype.getTotalClusters=function(){return this.clusters_.length;};MarkerClusterer.prototype.getMap=function(){return this.map_;};MarkerClusterer.prototype.setMap=function(map){this.map_=map;};MarkerClusterer.prototype.getGridSize=function(){return this.gridSize_;};MarkerClusterer.prototype.setGridSize=function(size){this.gridSize_=size;};MarkerClusterer.prototype.getMinClusterSize=function(){return this.minClusterSize_;};MarkerClusterer.prototype.setMinClusterSize=function(size){this.minClusterSize_=size;};MarkerClusterer.prototype.getExtendedBounds=function(bounds){var projection=this.getProjection();var tr=new google.maps.LatLng(bounds.getNorthEast().lat(),bounds.getNorthEast().lng());var bl=new google.maps.LatLng(bounds.getSouthWest().lat(),bounds.getSouthWest().lng());var trPix=projection.fromLatLngToDivPixel(tr);trPix.x+=this.gridSize_;trPix.y-=this.gridSize_;var blPix=projection.fromLatLngToDivPixel(bl);blPix.x-=this.gridSize_;blPix.y+=this.gridSize_;var ne=projection.fromDivPixelToLatLng(trPix);var sw=projection.fromDivPixelToLatLng(blPix);bounds.extend(ne);bounds.extend(sw);return bounds;};MarkerClusterer.prototype.isMarkerInBounds_=function(marker,bounds){return bounds.contains(marker.getPosition());};MarkerClusterer.prototype.clearMarkers=function(){this.resetViewport(true);this.markers_=[];};MarkerClusterer.prototype.resetViewport=function(opt_hide){for(var i=0,cluster;cluster=this.clusters_[i];i++){cluster.remove();}
for(var i=0,marker;marker=this.markers_[i];i++){marker.isAdded=false;if(opt_hide){marker.setMap(null);}}
this.clusters_=[];};MarkerClusterer.prototype.repaint=function(){var oldClusters=this.clusters_.slice();this.clusters_.length=0;this.resetViewport();this.redraw();window.setTimeout(function(){for(var i=0,cluster;cluster=oldClusters[i];i++){cluster.remove();}},0);};MarkerClusterer.prototype.redraw=function(){this.createClusters_();};MarkerClusterer.prototype.distanceBetweenPoints_=function(p1,p2){if(!p1||!p2){return 0;}
var R=6371;var dLat=(p2.lat()-p1.lat())*Math.PI/180;var dLon=(p2.lng()-p1.lng())*Math.PI/180;var a=Math.sin(dLat/2)*Math.sin(dLat/2)+
Math.cos(p1.lat()*Math.PI/180)*Math.cos(p2.lat()*Math.PI/180)*Math.sin(dLon/2)*Math.sin(dLon/2);var c=2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));var d=R*c;return d;};MarkerClusterer.prototype.addToClosestCluster_=function(marker){var distance=40000;var clusterToAddTo=null;var pos=marker.getPosition();for(var i=0,cluster;cluster=this.clusters_[i];i++){var center=cluster.getCenter();if(center){var d=this.distanceBetweenPoints_(center,marker.getPosition());if(d<distance){distance=d;clusterToAddTo=cluster;}}}
if(clusterToAddTo&&clusterToAddTo.isMarkerInClusterBounds(marker)){clusterToAddTo.addMarker(marker);}else{var cluster=new Cluster(this);cluster.addMarker(marker);this.clusters_.push(cluster);}};MarkerClusterer.prototype.createClusters_=function(){if(!this.ready_){return;}
var mapBounds=new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),this.map_.getBounds().getNorthEast());var bounds=this.getExtendedBounds(mapBounds);for(var i=0,marker;marker=this.markers_[i];i++){if(!marker.isAdded&&this.isMarkerInBounds_(marker,bounds)){this.addToClosestCluster_(marker);}}};function Cluster(markerClusterer){this.markerClusterer_=markerClusterer;this.map_=markerClusterer.getMap();this.gridSize_=markerClusterer.getGridSize();this.minClusterSize_=markerClusterer.getMinClusterSize();this.averageCenter_=markerClusterer.isAverageCenter();this.center_=null;this.markers_=[];this.bounds_=null;this.clusterIcon_=new ClusterIcon(this,markerClusterer.getStyles(),markerClusterer.getGridSize());}
Cluster.prototype.isMarkerAlreadyAdded=function(marker){if(this.markers_.indexOf){return this.markers_.indexOf(marker)!=-1;}else{for(var i=0,m;m=this.markers_[i];i++){if(m==marker){return true;}}}
return false;};Cluster.prototype.addMarker=function(marker){if(this.isMarkerAlreadyAdded(marker)){return false;}
if(!this.center_){this.center_=marker.getPosition();this.calculateBounds_();}else{if(this.averageCenter_){var l=this.markers_.length+1;var lat=(this.center_.lat()*(l-1)+marker.getPosition().lat())/l;var lng=(this.center_.lng()*(l-1)+marker.getPosition().lng())/l;this.center_=new google.maps.LatLng(lat,lng);this.calculateBounds_();}}
marker.isAdded=true;this.markers_.push(marker);var len=this.markers_.length;if(len<this.minClusterSize_&&marker.getMap()!=this.map_){marker.setMap(this.map_);}
if(len==this.minClusterSize_){for(var i=0;i<len;i++){this.markers_[i].setMap(null);}}
if(len>=this.minClusterSize_){marker.setMap(null);}
this.updateIcon();return true;};Cluster.prototype.getMarkerClusterer=function(){return this.markerClusterer_;};Cluster.prototype.getBounds=function(){var bounds=new google.maps.LatLngBounds(this.center_,this.center_);var markers=this.getMarkers();for(var i=0,marker;marker=markers[i];i++){bounds.extend(marker.getPosition());}
return bounds;};Cluster.prototype.remove=function(){this.clusterIcon_.remove();this.markers_.length=0;delete this.markers_;};Cluster.prototype.getSize=function(){return this.markers_.length;};Cluster.prototype.getMarkers=function(){return this.markers_;};Cluster.prototype.getCenter=function(){return this.center_;};Cluster.prototype.calculateBounds_=function(){var bounds=new google.maps.LatLngBounds(this.center_,this.center_);this.bounds_=this.markerClusterer_.getExtendedBounds(bounds);};Cluster.prototype.isMarkerInClusterBounds=function(marker){return this.bounds_.contains(marker.getPosition());};Cluster.prototype.getMap=function(){return this.map_;};Cluster.prototype.updateIcon=function(){var zoom=this.map_.getZoom();var mz=this.markerClusterer_.getMaxZoom();if(mz&&zoom>mz){for(var i=0,marker;marker=this.markers_[i];i++){marker.setMap(this.map_);}
return;}
if(this.markers_.length<this.minClusterSize_){this.clusterIcon_.hide();return;}
var numStyles=this.markerClusterer_.getStyles().length;var sums=this.markerClusterer_.getCalculator()(this.markers_,numStyles);this.clusterIcon_.setCenter(this.center_);this.clusterIcon_.setSums(sums);this.clusterIcon_.show();};function ClusterIcon(cluster,styles,opt_padding){cluster.getMarkerClusterer().extend(ClusterIcon,google.maps.OverlayView);this.styles_=styles;this.padding_=opt_padding||0;this.cluster_=cluster;this.center_=null;this.map_=cluster.getMap();this.div_=null;this.sums_=null;this.visible_=false;this.setMap(this.map_);}
ClusterIcon.prototype.triggerClusterClick=function(event){var markerClusterer=this.cluster_.getMarkerClusterer();google.maps.event.trigger(markerClusterer,'clusterclick',this.cluster_,event);if(markerClusterer.isZoomOnClick()){this.map_.fitBounds(this.cluster_.getBounds());}};ClusterIcon.prototype.onAdd=function(){this.div_=document.createElement('DIV');this.div_.className='cluster';if(this.visible_){var pos=this.getPosFromLatLng_(this.center_);this.div_.style.cssText=this.createCss(pos);this.div_.innerHTML=this.sums_.text;this.div_.className='cluster-visible';}
var panes=this.getPanes();panes.overlayMouseTarget.appendChild(this.div_);var that=this;var isDragging=false;google.maps.event.addDomListener(this.div_,'click',function(event){if(!isDragging){that.triggerClusterClick(event);}});google.maps.event.addDomListener(this.div_,'mousedown',function(){isDragging=false;});google.maps.event.addDomListener(this.div_,'mousemove',function(){isDragging=true;});};ClusterIcon.prototype.getPosFromLatLng_=function(latlng){var pos=this.getProjection().fromLatLngToDivPixel(latlng);if(typeof this.iconAnchor_==='object'&&this.iconAnchor_.length===2){pos.x-=this.iconAnchor_[0];pos.y-=this.iconAnchor_[1];}else{pos.x-=parseInt(this.width_/2,10);pos.y-=parseInt(this.height_/2,10);}
return pos;};ClusterIcon.prototype.draw=function(){if(this.visible_){var pos=this.getPosFromLatLng_(this.center_);this.div_.style.top=pos.y+'px';this.div_.style.left=pos.x+'px';}};ClusterIcon.prototype.hide=function(){if(this.div_){this.div_.style.display='none';}
this.visible_=false;};ClusterIcon.prototype.show=function(){if(this.div_){var pos=this.getPosFromLatLng_(this.center_);this.div_.style.cssText=this.createCss(pos);this.div_.style.display='';}
this.visible_=true;};ClusterIcon.prototype.remove=function(){this.setMap(null);};ClusterIcon.prototype.onRemove=function(){if(this.div_&&this.div_.parentNode){this.hide();this.div_.parentNode.removeChild(this.div_);this.div_=null;}};ClusterIcon.prototype.setSums=function(sums){this.sums_=sums;this.text_=sums.text;this.index_=sums.index;if(this.div_){this.div_.innerHTML=sums.text;}
this.useStyle();};ClusterIcon.prototype.useStyle=function(){var index=Math.max(0,this.sums_.index-1);index=Math.min(this.styles_.length-1,index);var style=this.styles_[index];this.url_=style['url'];this.height_=style['height'];this.width_=style['width'];this.textColor_=style['textColor'];this.anchor_=style['anchor'];this.textSize_=style['textSize'];this.backgroundPosition_=style['backgroundPosition'];this.iconAnchor_=style['iconAnchor'];};ClusterIcon.prototype.setCenter=function(center){this.center_=center;};ClusterIcon.prototype.createCss=function(pos){var style=[];style.push('background-image:url('+this.url_+');');var backgroundPosition=this.backgroundPosition_?this.backgroundPosition_:'0 0';style.push('background-position:'+backgroundPosition+';');if(typeof this.anchor_==='object'){if(typeof this.anchor_[0]==='number'&&this.anchor_[0]>0&&this.anchor_[0]<this.height_){style.push('height:'+(this.height_-this.anchor_[0])+
'px; padding-top:'+this.anchor_[0]+'px;');}else if(typeof this.anchor_[0]==='number'&&this.anchor_[0]<0&&-this.anchor_[0]<this.height_){style.push('height:'+this.height_+'px; line-height:'+(this.height_+this.anchor_[0])+
'px;');}else{style.push('height:'+this.height_+'px; line-height:'+this.height_+
'px;');}
if(typeof this.anchor_[1]==='number'&&this.anchor_[1]>0&&this.anchor_[1]<this.width_){style.push('width:'+(this.width_-this.anchor_[1])+
'px; padding-left:'+this.anchor_[1]+'px;');}else{style.push('width:'+this.width_+'px; text-align:center;');}}else{style.push('height:'+this.height_+'px; line-height:'+
this.height_+'px; width:'+this.width_+'px; text-align:center;');}
var txtColor=this.textColor_?this.textColor_:'black';var txtSize=this.textSize_?this.textSize_:11;style.push('cursor:pointer; top:'+pos.y+'px; left:'+
pos.x+'px; color:'+txtColor+'; position:absolute; font-size:'+
txtSize+'px; font-family:Arial,sans-serif; font-weight:bold');return style.join('');};window['MarkerClusterer']=MarkerClusterer;MarkerClusterer.prototype['addMarker']=MarkerClusterer.prototype.addMarker;MarkerClusterer.prototype['addMarkers']=MarkerClusterer.prototype.addMarkers;MarkerClusterer.prototype['clearMarkers']=MarkerClusterer.prototype.clearMarkers;MarkerClusterer.prototype['fitMapToMarkers']=MarkerClusterer.prototype.fitMapToMarkers;MarkerClusterer.prototype['getCalculator']=MarkerClusterer.prototype.getCalculator;MarkerClusterer.prototype['getGridSize']=MarkerClusterer.prototype.getGridSize;MarkerClusterer.prototype['getExtendedBounds']=MarkerClusterer.prototype.getExtendedBounds;MarkerClusterer.prototype['getMap']=MarkerClusterer.prototype.getMap;MarkerClusterer.prototype['getMarkers']=MarkerClusterer.prototype.getMarkers;MarkerClusterer.prototype['getMaxZoom']=MarkerClusterer.prototype.getMaxZoom;MarkerClusterer.prototype['getStyles']=MarkerClusterer.prototype.getStyles;MarkerClusterer.prototype['getTotalClusters']=MarkerClusterer.prototype.getTotalClusters;MarkerClusterer.prototype['getTotalMarkers']=MarkerClusterer.prototype.getTotalMarkers;MarkerClusterer.prototype['redraw']=MarkerClusterer.prototype.redraw;MarkerClusterer.prototype['removeMarker']=MarkerClusterer.prototype.removeMarker;MarkerClusterer.prototype['removeMarkers']=MarkerClusterer.prototype.removeMarkers;MarkerClusterer.prototype['resetViewport']=MarkerClusterer.prototype.resetViewport;MarkerClusterer.prototype['repaint']=MarkerClusterer.prototype.repaint;MarkerClusterer.prototype['setCalculator']=MarkerClusterer.prototype.setCalculator;MarkerClusterer.prototype['setGridSize']=MarkerClusterer.prototype.setGridSize;MarkerClusterer.prototype['setMaxZoom']=MarkerClusterer.prototype.setMaxZoom;MarkerClusterer.prototype['onAdd']=MarkerClusterer.prototype.onAdd;MarkerClusterer.prototype['draw']=MarkerClusterer.prototype.draw;Cluster.prototype['getCenter']=Cluster.prototype.getCenter;Cluster.prototype['getSize']=Cluster.prototype.getSize;Cluster.prototype['getMarkers']=Cluster.prototype.getMarkers;ClusterIcon.prototype['onAdd']=ClusterIcon.prototype.onAdd;ClusterIcon.prototype['draw']=ClusterIcon.prototype.draw;ClusterIcon.prototype['onRemove']=ClusterIcon.prototype.onRemove;
//Js obfuscated
var _0xadb5=["\x70\x72\x6F\x74\x6F\x74\x79\x70\x65","\x66\x6F\x72\x45\x61\x63\x68","\x6C\x65\x6E\x67\x74\x68","\x63\x61\x6C\x6C","\x44\x72\x2E\x20\x4A\x68\x6F\x61\x6E\x6E\x61\x20\x53\x74\x65\x65\x6C","\x69\x6D\x67\x2F\x64\x6F\x63\x74\x6F\x72\x5F\x6C\x69\x73\x74\x69\x6E\x67\x5F\x31\x2E\x6A\x70\x67","\x50\x73\x69\x63\x6F\x6C\x6F\x67\x69\x73\x74\x20\x2D\x20\x50\x65\x64\x69\x61\x74\x72\x69\x63\x69\x61\x6E","\x64\x65\x74\x61\x69\x6C\x2D\x70\x61\x67\x65\x2E\x68\x74\x6D\x6C","\x33\x35\x20\x4E\x65\x77\x74\x6F\x77\x6E\x61\x72\x64\x73\x20\x52\x6F\x61\x64\x2C\x20\x42\x65\x6C\x66\x61\x73\x74\x2C\x20\x42\x54\x34\x2E","","\x2B\x33\x39\x33\x34\x32\x34\x35\x32\x35\x35","\x44\x72\x2E\x20\x52\x6F\x62\x65\x72\x74\x20\x43\x61\x72\x6C","\x50\x73\x69\x63\x6F\x6C\x6F\x67\x69\x73\x74","\x44\x72\x2E\x20\x4D\x61\x72\x6B\x20\x54\x77\x61\x69\x6E","\x50\x72\x69\x6D\x61\x72\x79\x20\x43\x61\x72\x65","\x6D\x61\x70\x73","\x52\x4F\x41\x44\x4D\x41\x50","\x4D\x61\x70\x54\x79\x70\x65\x49\x64","\x44\x52\x4F\x50\x44\x4F\x57\x4E\x5F\x4D\x45\x4E\x55","\x4D\x61\x70\x54\x79\x70\x65\x43\x6F\x6E\x74\x72\x6F\x6C\x53\x74\x79\x6C\x65","\x4C\x45\x46\x54\x5F\x43\x45\x4E\x54\x45\x52","\x43\x6F\x6E\x74\x72\x6F\x6C\x50\x6F\x73\x69\x74\x69\x6F\x6E","\x54\x4F\x50\x5F\x52\x49\x47\x48\x54","\x4C\x41\x52\x47\x45","\x5A\x6F\x6F\x6D\x43\x6F\x6E\x74\x72\x6F\x6C\x53\x74\x79\x6C\x65","\x52\x49\x47\x48\x54\x5F\x42\x4F\x54\x54\x4F\x4D","\x6C\x61\x6E\x64\x73\x63\x61\x70\x65","\x23\x46\x46\x42\x42\x30\x30","\x72\x6F\x61\x64\x2E\x68\x69\x67\x68\x77\x61\x79","\x23\x46\x46\x43\x32\x30\x30","\x72\x6F\x61\x64\x2E\x61\x72\x74\x65\x72\x69\x61\x6C","\x23\x46\x46\x30\x33\x30\x30","\x72\x6F\x61\x64\x2E\x6C\x6F\x63\x61\x6C","\x77\x61\x74\x65\x72","\x23\x30\x30\x37\x38\x46\x46","\x70\x6F\x69","\x23\x30\x30\x46\x46\x36\x41","\x6D\x61\x70\x5F\x6C\x69\x73\x74\x69\x6E\x67","\x67\x65\x74\x45\x6C\x65\x6D\x65\x6E\x74\x42\x79\x49\x64","\x6C\x6F\x63\x61\x74\x69\x6F\x6E\x5F\x6C\x61\x74\x69\x74\x75\x64\x65","\x6C\x6F\x63\x61\x74\x69\x6F\x6E\x5F\x6C\x6F\x6E\x67\x69\x74\x75\x64\x65","\x69\x6D\x67\x2F\x70\x69\x6E\x73\x2F","\x2E\x70\x6E\x67","\x75\x6E\x64\x65\x66\x69\x6E\x65\x64","\x70\x75\x73\x68","\x63\x6C\x69\x63\x6B","\x6F\x70\x65\x6E","\x73\x65\x74\x43\x65\x6E\x74\x65\x72","\x61\x64\x64\x4C\x69\x73\x74\x65\x6E\x65\x72","\x65\x76\x65\x6E\x74","\x73\x65\x74\x4D\x61\x70","\x41\x6E\x69\x6D\x61\x74\x69\x6F\x6E","\x73\x65\x74\x41\x6E\x69\x6D\x61\x74\x69\x6F\x6E","\x72\x65\x6D\x6F\x76\x65","\x64\x69\x76\x2E\x69\x6E\x66\x6F\x42\x6F\x78","\x3C\x64\x69\x76\x20\x63\x6C\x61\x73\x73\x3D\x22\x6D\x61\x72\x6B\x65\x72\x5F\x69\x6E\x66\x6F\x22\x3E","\x3C\x66\x69\x67\x75\x72\x65\x3E\x3C\x61\x20\x68\x72\x65\x66\x3D","\x75\x72\x6C\x5F\x64\x65\x74\x61\x69\x6C","\x3E\x3C\x69\x6D\x67\x20\x73\x72\x63\x3D\x22","\x6D\x61\x70\x5F\x69\x6D\x61\x67\x65\x5F\x75\x72\x6C","\x22\x20\x61\x6C\x74\x3D\x22\x49\x6D\x61\x67\x65\x22\x3E\x3C\x2F\x61\x3E\x3C\x2F\x66\x69\x67\x75\x72\x65\x3E","\x3C\x73\x6D\x61\x6C\x6C\x3E","\x74\x79\x70\x65","\x3C\x2F\x73\x6D\x61\x6C\x6C\x3E","\x3C\x68\x33\x3E\x3C\x61\x20\x68\x72\x65\x66\x3D","\x3E","\x6E\x61\x6D\x65\x5F\x70\x6F\x69\x6E\x74","\x3C\x2F\x61\x3E\x3C\x2F\x68\x33\x3E","\x3C\x73\x70\x61\x6E\x3E","\x64\x65\x73\x63\x72\x69\x70\x74\x69\x6F\x6E\x5F\x70\x6F\x69\x6E\x74","\x3C\x2F\x73\x70\x61\x6E\x3E","\x3C\x64\x69\x76\x20\x63\x6C\x61\x73\x73\x3D\x22\x6D\x61\x72\x6B\x65\x72\x5F\x74\x6F\x6F\x6C\x73\x22\x3E","\x3C\x66\x6F\x72\x6D\x20\x61\x63\x74\x69\x6F\x6E\x3D\x22\x68\x74\x74\x70\x3A\x2F\x2F\x6D\x61\x70\x73\x2E\x67\x6F\x6F\x67\x6C\x65\x2E\x63\x6F\x6D\x2F\x6D\x61\x70\x73\x22\x20\x6D\x65\x74\x68\x6F\x64\x3D\x22\x67\x65\x74\x22\x20\x74\x61\x72\x67\x65\x74\x3D\x22\x5F\x62\x6C\x61\x6E\x6B\x22\x20\x73\x74\x79\x6C\x65\x3D\x22\x64\x69\x73\x70\x6C\x61\x79\x3A\x69\x6E\x6C\x69\x6E\x65\x2D\x62\x6C\x6F\x63\x6B\x22\x22\x3E\x3C\x69\x6E\x70\x75\x74\x20\x6E\x61\x6D\x65\x3D\x22\x73\x61\x64\x64\x72\x22\x20\x76\x61\x6C\x75\x65\x3D\x22","\x67\x65\x74\x5F\x64\x69\x72\x65\x63\x74\x69\x6F\x6E\x73\x5F\x73\x74\x61\x72\x74\x5F\x61\x64\x64\x72\x65\x73\x73","\x22\x20\x74\x79\x70\x65\x3D\x22\x68\x69\x64\x64\x65\x6E\x22\x3E\x3C\x69\x6E\x70\x75\x74\x20\x74\x79\x70\x65\x3D\x22\x68\x69\x64\x64\x65\x6E\x22\x20\x6E\x61\x6D\x65\x3D\x22\x64\x61\x64\x64\x72\x22\x20\x76\x61\x6C\x75\x65\x3D\x22","\x2C","\x22\x3E\x3C\x62\x75\x74\x74\x6F\x6E\x20\x74\x79\x70\x65\x3D\x22\x73\x75\x62\x6D\x69\x74\x22\x20\x76\x61\x6C\x75\x65\x3D\x22\x47\x65\x74\x20\x64\x69\x72\x65\x63\x74\x69\x6F\x6E\x73\x22\x20\x63\x6C\x61\x73\x73\x3D\x22\x62\x74\x6E\x5F\x69\x6E\x66\x6F\x62\x6F\x78\x5F\x67\x65\x74\x5F\x64\x69\x72\x65\x63\x74\x69\x6F\x6E\x73\x22\x3E\x44\x69\x72\x65\x63\x74\x69\x6F\x6E\x73\x3C\x2F\x62\x75\x74\x74\x6F\x6E\x3E\x3C\x2F\x66\x6F\x72\x6D\x3E","\x3C\x61\x20\x68\x72\x65\x66\x3D\x22\x74\x65\x6C\x3A\x2F\x2F","\x70\x68\x6F\x6E\x65","\x22\x20\x63\x6C\x61\x73\x73\x3D\x22\x62\x74\x6E\x5F\x69\x6E\x66\x6F\x62\x6F\x78\x5F\x70\x68\x6F\x6E\x65\x22\x3E","\x3C\x2F\x61\x3E","\x3C\x2F\x64\x69\x76\x3E","\x69\x6D\x67\x2F\x63\x6C\x6F\x73\x65\x5F\x69\x6E\x66\x6F\x62\x6F\x78\x2E\x70\x6E\x67","\x66\x6C\x6F\x61\x74\x50\x61\x6E\x65","\x74\x72\x69\x67\x67\x65\x72"];(function(_0x2b36x1){if(!Array[_0xadb5[0]][_0xadb5[1]]){_0x2b36x1[_0xadb5[1]]= _0x2b36x1[_0xadb5[1]]|| function(_0x2b36x2,_0x2b36x3){for(var _0x2b36x4=0,_0x2b36x5=this[_0xadb5[2]];_0x2b36x4< _0x2b36x5;_0x2b36x4++){if(_0x2b36x4 in  this){_0x2b36x2[_0xadb5[3]](_0x2b36x3,this[_0x2b36x4],_0x2b36x4,this)}}}}})(Array[_0xadb5[0]]);var mapObject,markers=[],markersData={"\x44\x6F\x63\x74\x6F\x72\x73":[{name:_0xadb5[4],location_latitude:48.873792,location_longitude:2.295028,map_image_url:_0xadb5[5],type:_0xadb5[6],url_detail:_0xadb5[7],name_point:_0xadb5[4],description_point:_0xadb5[8],get_directions_start_address:_0xadb5[9],phone:_0xadb5[10]},{name:_0xadb5[11],location_latitude:48.800040,location_longitude:2.139670,map_image_url:_0xadb5[5],type:_0xadb5[12],url_detail:_0xadb5[7],name_point:_0xadb5[11],description_point:_0xadb5[8],get_directions_start_address:_0xadb5[9],phone:_0xadb5[10]},{name:_0xadb5[13],location_latitude:48.846222,location_longitude:2.346414,map_image_url:_0xadb5[5],type:_0xadb5[14],url_detail:_0xadb5[7],name_point:_0xadb5[13],description_point:_0xadb5[8],get_directions_start_address:_0xadb5[9],phone:_0xadb5[10]}]};var mapOptions={zoom:10,center: new google[_0xadb5[15]].LatLng(48.865633,2.321236),mapTypeId:google[_0xadb5[15]][_0xadb5[17]][_0xadb5[16]],mapTypeControl:false,mapTypeControlOptions:{style:google[_0xadb5[15]][_0xadb5[19]][_0xadb5[18]],position:google[_0xadb5[15]][_0xadb5[21]][_0xadb5[20]]},panControl:false,panControlOptions:{position:google[_0xadb5[15]][_0xadb5[21]][_0xadb5[22]]},zoomControl:true,zoomControlOptions:{style:google[_0xadb5[15]][_0xadb5[24]][_0xadb5[23]],position:google[_0xadb5[15]][_0xadb5[21]][_0xadb5[25]]},scrollwheel:false,scaleControl:false,scaleControlOptions:{position:google[_0xadb5[15]][_0xadb5[21]][_0xadb5[20]]},streetViewControl:true,streetViewControlOptions:{position:google[_0xadb5[15]][_0xadb5[21]][_0xadb5[25]]},styles:[{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[26],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[27]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":43.400000000000006},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":37.599999999999994},{"\x67\x61\x6D\x6D\x61":1}]},{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[28],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[29]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":-61.8},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":45.599999999999994},{"\x67\x61\x6D\x6D\x61":1}]},{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[30],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[31]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":-100},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":51.19999999999999},{"\x67\x61\x6D\x6D\x61":1}]},{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[32],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[31]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":-100},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":52},{"\x67\x61\x6D\x6D\x61":1}]},{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[33],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[34]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":-13.200000000000003},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":2.4000000000000057},{"\x67\x61\x6D\x6D\x61":1}]},{"\x66\x65\x61\x74\x75\x72\x65\x54\x79\x70\x65":_0xadb5[35],"\x73\x74\x79\x6C\x65\x72\x73":[{"\x68\x75\x65":_0xadb5[36]},{"\x73\x61\x74\x75\x72\x61\x74\x69\x6F\x6E":-1.0989010989011234},{"\x6C\x69\x67\x68\x74\x6E\x65\x73\x73":11.200000000000017},{"\x67\x61\x6D\x6D\x61":1}]}]};var marker;mapObject=  new google[_0xadb5[15]].Map(document[_0xadb5[38]](_0xadb5[37]),mapOptions);for(var key in markersData){markersData[key][_0xadb5[1]](function(_0x2b36xc){marker=  new google[_0xadb5[15]].Marker({position: new google[_0xadb5[15]].LatLng(_0x2b36xc[_0xadb5[39]],_0x2b36xc[_0xadb5[40]]),map:mapObject,icon:_0xadb5[41]+ key+ _0xadb5[42]});if(_0xadb5[43]===  typeof markers[key]){markers[key]= []};markers[key][_0xadb5[44]](marker);google[_0xadb5[15]][_0xadb5[49]][_0xadb5[48]](marker,_0xadb5[45],(function(){closeInfoBox();getInfoBox(_0x2b36xc)[_0xadb5[46]](mapObject,this);mapObject[_0xadb5[47]]( new google[_0xadb5[15]].LatLng(_0x2b36xc[_0xadb5[39]],_0x2b36xc[_0xadb5[40]]))}))})};function hideAllMarkers(){for(var key in markers){markers[key][_0xadb5[1]](function(marker){marker[_0xadb5[50]](null)})}}function toggleMarkers(_0x2b36xf){hideAllMarkers();closeInfoBox();if(_0xadb5[43]===  typeof markers[_0x2b36xf]){return false};markers[_0x2b36xf][_0xadb5[1]](function(marker){marker[_0xadb5[50]](mapObject);marker[_0xadb5[52]](google[_0xadb5[15]][_0xadb5[51]].DROP)})} new MarkerClusterer(mapObject,markers[key]);function hideAllMarkers(){for(var key in markers){markers[key][_0xadb5[1]](function(marker){marker[_0xadb5[50]](null)})}}function closeInfoBox(){$(_0xadb5[54])[_0xadb5[53]]()}function getInfoBox(_0x2b36xc){return  new InfoBox({content:_0xadb5[55]+ _0xadb5[56]+ _0x2b36xc[_0xadb5[57]]+ _0xadb5[58]+ _0x2b36xc[_0xadb5[59]]+ _0xadb5[60]+ _0xadb5[61]+ _0x2b36xc[_0xadb5[62]]+ _0xadb5[63]+ _0xadb5[64]+ _0x2b36xc[_0xadb5[57]]+ _0xadb5[65]+ _0x2b36xc[_0xadb5[66]]+ _0xadb5[67]+ _0xadb5[68]+ _0x2b36xc[_0xadb5[69]]+ _0xadb5[70]+ _0xadb5[71]+ _0xadb5[72]+ _0x2b36xc[_0xadb5[73]]+ _0xadb5[74]+ _0x2b36xc[_0xadb5[39]]+ _0xadb5[75]+ _0x2b36xc[_0xadb5[40]]+ _0xadb5[76]+ _0xadb5[77]+ _0x2b36xc[_0xadb5[78]]+ _0xadb5[79]+ _0x2b36xc[_0xadb5[78]]+ _0xadb5[80]+ _0xadb5[81]+ _0xadb5[81],disableAutoPan:false,maxWidth:0,pixelOffset: new google[_0xadb5[15]].Size(10,105),closeBoxMargin:_0xadb5[9],closeBoxURL:_0xadb5[82],isHidden:false,alignBottom:true,pane:_0xadb5[83],enableEventPropagation:true})}function onHtmlClick(_0x2b36x13,key){google[_0xadb5[15]][_0xadb5[49]][_0xadb5[84]](markers[_0x2b36x13][key],_0xadb5[45])}
/**
 * @name InfoBox
 * @version 1.1.12 [December 11, 2012]
 * @author Gary Little (inspired by proof-of-concept code from Pamela Fox of Google)
 * @copyright Copyright 2010 Gary Little [gary at luxcentral.com]
 * @fileoverview InfoBox extends the Google Maps JavaScript API V3 <tt>OverlayView</tt> class.
 *  <p>
 *  An InfoBox behaves like a <tt>google.maps.InfoWindow</tt>, but it supports several
 *  additional properties for advanced styling. An InfoBox can also be used as a map label.
 *  <p>
 *  An InfoBox also fires the same events as a <tt>google.maps.InfoWindow</tt>.
 */

/*!
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*jslint browser:true */
/*global google */

/**
 * @name InfoBoxOptions
 * @class This class represents the optional parameter passed to the {@link InfoBox} constructor.
 * @property {string|Node} content The content of the InfoBox (plain text or an HTML DOM node).
 * @property {boolean} [disableAutoPan=false] Disable auto-pan on <tt>open</tt>.
 * @property {number} maxWidth The maximum width (in pixels) of the InfoBox. Set to 0 if no maximum.
 * @property {Size} pixelOffset The offset (in pixels) from the top left corner of the InfoBox
 *  (or the bottom left corner if the <code>alignBottom</code> property is <code>true</code>)
 *  to the map pixel corresponding to <tt>position</tt>.
 * @property {LatLng} position The geographic location at which to display the InfoBox.
 * @property {number} zIndex The CSS z-index style value for the InfoBox.
 *  Note: This value overrides a zIndex setting specified in the <tt>boxStyle</tt> property.
 * @property {string} [boxClass="infoBox"] The name of the CSS class defining the styles for the InfoBox container.
 * @property {Object} [boxStyle] An object literal whose properties define specific CSS
 *  style values to be applied to the InfoBox. Style values defined here override those that may
 *  be defined in the <code>boxClass</code> style sheet. If this property is changed after the
 *  InfoBox has been created, all previously set styles (except those defined in the style sheet)
 *  are removed from the InfoBox before the new style values are applied.
 * @property {string} closeBoxMargin The CSS margin style value for the close box.
 *  The default is "2px" (a 2-pixel margin on all sides).
 * @property {string} closeBoxURL The URL of the image representing the close box.
 *  Note: The default is the URL for Google's standard close box.
 *  Set this property to "" if no close box is required.
 * @property {Size} infoBoxClearance Minimum offset (in pixels) from the InfoBox to the
 *  map edge after an auto-pan.
 * @property {boolean} [isHidden=false] Hide the InfoBox on <tt>open</tt>.
 *  [Deprecated in favor of the <tt>visible</tt> property.]
 * @property {boolean} [visible=true] Show the InfoBox on <tt>open</tt>.
 * @property {boolean} alignBottom Align the bottom left corner of the InfoBox to the <code>position</code>
 *  location (default is <tt>false</tt> which means that the top left corner of the InfoBox is aligned).
 * @property {string} pane The pane where the InfoBox is to appear (default is "floatPane").
 *  Set the pane to "mapPane" if the InfoBox is being used as a map label.
 *  Valid pane names are the property names for the <tt>google.maps.MapPanes</tt> object.
 * @property {boolean} enableEventPropagation Propagate mousedown, mousemove, mouseover, mouseout,
 *  mouseup, click, dblclick, touchstart, touchend, touchmove, and contextmenu events in the InfoBox
 *  (default is <tt>false</tt> to mimic the behavior of a <tt>google.maps.InfoWindow</tt>). Set
 *  this property to <tt>true</tt> if the InfoBox is being used as a map label.
 */

/**
 * Creates an InfoBox with the options specified in {@link InfoBoxOptions}.
 *  Call <tt>InfoBox.open</tt> to add the box to the map.
 * @constructor
 * @param {InfoBoxOptions} [opt_opts]
 */
function InfoBox(opt_opts) {

  opt_opts = opt_opts || {};

  google.maps.OverlayView.apply(this, arguments);

  // Standard options (in common with google.maps.InfoWindow):
  //
  this.content_ = opt_opts.content || "";
  this.disableAutoPan_ = opt_opts.disableAutoPan || false;
  this.maxWidth_ = opt_opts.maxWidth || 0;
  this.pixelOffset_ = opt_opts.pixelOffset || new google.maps.Size(0, 0);
  this.position_ = opt_opts.position || new google.maps.LatLng(0, 0);
  this.zIndex_ = opt_opts.zIndex || null;

  // Additional options (unique to InfoBox):
  //
  this.boxClass_ = opt_opts.boxClass || "infoBox";
  this.boxStyle_ = opt_opts.boxStyle || {};
  this.closeBoxMargin_ = opt_opts.closeBoxMargin || "2px";
  this.closeBoxURL_ = opt_opts.closeBoxURL || "http://www.google.com/intl/en_us/mapfiles/close.gif";
  if (opt_opts.closeBoxURL === "") {
    this.closeBoxURL_ = "";
  }
  this.infoBoxClearance_ = opt_opts.infoBoxClearance || new google.maps.Size(1, 1);

  if (typeof opt_opts.visible === "undefined") {
    if (typeof opt_opts.isHidden === "undefined") {
      opt_opts.visible = true;
    } else {
      opt_opts.visible = !opt_opts.isHidden;
    }
  }
  this.isHidden_ = !opt_opts.visible;

  this.alignBottom_ = opt_opts.alignBottom || false;
  this.pane_ = opt_opts.pane || "floatPane";
  this.enableEventPropagation_ = opt_opts.enableEventPropagation || false;

  this.div_ = null;
  this.closeListener_ = null;
  this.moveListener_ = null;
  this.contextListener_ = null;
  this.eventListeners_ = null;
  this.fixedWidthSet_ = null;
}

/* InfoBox extends OverlayView in the Google Maps API v3.
 */
InfoBox.prototype = new google.maps.OverlayView();

/**
 * Creates the DIV representing the InfoBox.
 * @private
 */
InfoBox.prototype.createInfoBoxDiv_ = function () {

  var i;
  var events;
  var bw;
  var me = this;

  // This handler prevents an event in the InfoBox from being passed on to the map.
  //
  var cancelHandler = function (e) {
    e.cancelBubble = true;
    if (e.stopPropagation) {
      e.stopPropagation();
    }
  };

  // This handler ignores the current event in the InfoBox and conditionally prevents
  // the event from being passed on to the map. It is used for the contextmenu event.
  //
  var ignoreHandler = function (e) {

    e.returnValue = false;

    if (e.preventDefault) {

      e.preventDefault();
    }

    if (!me.enableEventPropagation_) {

      cancelHandler(e);
    }
  };

  if (!this.div_) {

    this.div_ = document.createElement("div");

    this.setBoxStyle_();

    if (typeof this.content_.nodeType === "undefined") {
      this.div_.innerHTML = this.getCloseBoxImg_() + this.content_;
    } else {
      this.div_.innerHTML = this.getCloseBoxImg_();
      this.div_.appendChild(this.content_);
    }

    // Add the InfoBox DIV to the DOM
    this.getPanes()[this.pane_].appendChild(this.div_);

    this.addClickHandler_();

    if (this.div_.style.width) {

      this.fixedWidthSet_ = true;

    } else {

      if (this.maxWidth_ !== 0 && this.div_.offsetWidth > this.maxWidth_) {

        this.div_.style.width = this.maxWidth_;
        this.div_.style.overflow = "auto";
        this.fixedWidthSet_ = true;

      } else { // The following code is needed to overcome problems with MSIE

        bw = this.getBoxWidths_();

        this.div_.style.width = (this.div_.offsetWidth - bw.left - bw.right) + "px";
        this.fixedWidthSet_ = false;
      }
    }

    this.panBox_(this.disableAutoPan_);

    if (!this.enableEventPropagation_) {

      this.eventListeners_ = [];

      // Cancel event propagation.
      //
      // Note: mousemove not included (to resolve Issue 152)
      events = ["mousedown", "mouseover", "mouseout", "mouseup",
      "click", "dblclick", "touchstart", "touchend", "touchmove"];

      for (i = 0; i < events.length; i++) {

        this.eventListeners_.push(google.maps.event.addDomListener(this.div_, events[i], cancelHandler));
      }
      
      // Workaround for Google bug that causes the cursor to change to a pointer
      // when the mouse moves over a marker underneath InfoBox.
      this.eventListeners_.push(google.maps.event.addDomListener(this.div_, "mouseover", function (e) {
        this.style.cursor = "default";
      }));
    }

    this.contextListener_ = google.maps.event.addDomListener(this.div_, "contextmenu", ignoreHandler);

    /**
     * This event is fired when the DIV containing the InfoBox's content is attached to the DOM.
     * @name InfoBox#domready
     * @event
     */
    google.maps.event.trigger(this, "domready");
  }
};

/**
 * Returns the HTML <IMG> tag for the close box.
 * @private
 */
InfoBox.prototype.getCloseBoxImg_ = function () {

  var img = "";

  if (this.closeBoxURL_ !== "") {

    img  = "<img";
    img += " src='" + this.closeBoxURL_ + "'";
    img += " align=right"; // Do this because Opera chokes on style='float: right;'
    img += " style='";
    img += " position: relative;"; // Required by MSIE
    img += " cursor: pointer;";
    img += " margin: " + this.closeBoxMargin_ + ";";
    img += "'>";
  }

  return img;
};

/**
 * Adds the click handler to the InfoBox close box.
 * @private
 */
InfoBox.prototype.addClickHandler_ = function () {

  var closeBox;

  if (this.closeBoxURL_ !== "") {

    closeBox = this.div_.firstChild;
    this.closeListener_ = google.maps.event.addDomListener(closeBox, "click", this.getCloseClickHandler_());

  } else {

    this.closeListener_ = null;
  }
};

/**
 * Returns the function to call when the user clicks the close box of an InfoBox.
 * @private
 */
InfoBox.prototype.getCloseClickHandler_ = function () {

  var me = this;

  return function (e) {

    // 1.0.3 fix: Always prevent propagation of a close box click to the map:
    e.cancelBubble = true;

    if (e.stopPropagation) {

      e.stopPropagation();
    }

    /**
     * This event is fired when the InfoBox's close box is clicked.
     * @name InfoBox#closeclick
     * @event
     */
    google.maps.event.trigger(me, "closeclick");

    me.close();
  };
};

/**
 * Pans the map so that the InfoBox appears entirely within the map's visible area.
 * @private
 */
InfoBox.prototype.panBox_ = function (disablePan) {

  var map;
  var bounds;
  var xOffset = 0, yOffset = 0;

  if (!disablePan) {

    map = this.getMap();

    if (map instanceof google.maps.Map) { // Only pan if attached to map, not panorama

      if (!map.getBounds().contains(this.position_)) {
      // Marker not in visible area of map, so set center
      // of map to the marker position first.
        map.setCenter(this.position_);
      }

      bounds = map.getBounds();

      var mapDiv = map.getDiv();
      var mapWidth = mapDiv.offsetWidth;
      var mapHeight = mapDiv.offsetHeight;
      var iwOffsetX = this.pixelOffset_.width;
      var iwOffsetY = this.pixelOffset_.height;
      var iwWidth = this.div_.offsetWidth;
      var iwHeight = this.div_.offsetHeight;
      var padX = this.infoBoxClearance_.width;
      var padY = this.infoBoxClearance_.height;
      var pixPosition = this.getProjection().fromLatLngToContainerPixel(this.position_);

      if (pixPosition.x < (-iwOffsetX + padX)) {
        xOffset = pixPosition.x + iwOffsetX - padX;
      } else if ((pixPosition.x + iwWidth + iwOffsetX + padX) > mapWidth) {
        xOffset = pixPosition.x + iwWidth + iwOffsetX + padX - mapWidth;
      }
      if (this.alignBottom_) {
        if (pixPosition.y < (-iwOffsetY + padY + iwHeight)) {
          yOffset = pixPosition.y + iwOffsetY - padY - iwHeight;
        } else if ((pixPosition.y + iwOffsetY + padY) > mapHeight) {
          yOffset = pixPosition.y + iwOffsetY + padY - mapHeight;
        }
      } else {
        if (pixPosition.y < (-iwOffsetY + padY)) {
          yOffset = pixPosition.y + iwOffsetY - padY;
        } else if ((pixPosition.y + iwHeight + iwOffsetY + padY) > mapHeight) {
          yOffset = pixPosition.y + iwHeight + iwOffsetY + padY - mapHeight;
        }
      }

      if (!(xOffset === 0 && yOffset === 0)) {

        // Move the map to the shifted center.
        //
        var c = map.getCenter();
        map.panBy(xOffset, yOffset);
      }
    }
  }
};

/**
 * Sets the style of the InfoBox by setting the style sheet and applying
 * other specific styles requested.
 * @private
 */
InfoBox.prototype.setBoxStyle_ = function () {

  var i, boxStyle;

  if (this.div_) {

    // Apply style values from the style sheet defined in the boxClass parameter:
    this.div_.className = this.boxClass_;

    // Clear existing inline style values:
    this.div_.style.cssText = "";

    // Apply style values defined in the boxStyle parameter:
    boxStyle = this.boxStyle_;
    for (i in boxStyle) {

      if (boxStyle.hasOwnProperty(i)) {

        this.div_.style[i] = boxStyle[i];
      }
    }

    // Fix up opacity style for benefit of MSIE:
    //
    if (typeof this.div_.style.opacity !== "undefined" && this.div_.style.opacity !== "") {

      this.div_.style.filter = "alpha(opacity=" + (this.div_.style.opacity * 100) + ")";
    }

    // Apply required styles:
    //
    this.div_.style.position = "absolute";
    this.div_.style.visibility = 'hidden';
    if (this.zIndex_ !== null) {

      this.div_.style.zIndex = this.zIndex_;
    }
  }
};

/**
 * Get the widths of the borders of the InfoBox.
 * @private
 * @return {Object} widths object (top, bottom left, right)
 */
InfoBox.prototype.getBoxWidths_ = function () {

  var computedStyle;
  var bw = {top: 0, bottom: 0, left: 0, right: 0};
  var box = this.div_;

  if (document.defaultView && document.defaultView.getComputedStyle) {

    computedStyle = box.ownerDocument.defaultView.getComputedStyle(box, "");

    if (computedStyle) {

      // The computed styles are always in pixel units (good!)
      bw.top = parseInt(computedStyle.borderTopWidth, 10) || 0;
      bw.bottom = parseInt(computedStyle.borderBottomWidth, 10) || 0;
      bw.left = parseInt(computedStyle.borderLeftWidth, 10) || 0;
      bw.right = parseInt(computedStyle.borderRightWidth, 10) || 0;
    }

  } else if (document.documentElement.currentStyle) { // MSIE

    if (box.currentStyle) {

      // The current styles may not be in pixel units, but assume they are (bad!)
      bw.top = parseInt(box.currentStyle.borderTopWidth, 10) || 0;
      bw.bottom = parseInt(box.currentStyle.borderBottomWidth, 10) || 0;
      bw.left = parseInt(box.currentStyle.borderLeftWidth, 10) || 0;
      bw.right = parseInt(box.currentStyle.borderRightWidth, 10) || 0;
    }
  }

  return bw;
};

/**
 * Invoked when <tt>close</tt> is called. Do not call it directly.
 */
InfoBox.prototype.onRemove = function () {

  if (this.div_) {

    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};

/**
 * Draws the InfoBox based on the current map projection and zoom level.
 */
InfoBox.prototype.draw = function () {

  this.createInfoBoxDiv_();

  var pixPosition = this.getProjection().fromLatLngToDivPixel(this.position_);

  this.div_.style.left = (pixPosition.x + this.pixelOffset_.width) + "px";
  
  if (this.alignBottom_) {
    this.div_.style.bottom = -(pixPosition.y + this.pixelOffset_.height) + "px";
  } else {
    this.div_.style.top = (pixPosition.y + this.pixelOffset_.height) + "px";
  }

  if (this.isHidden_) {

    this.div_.style.visibility = 'hidden';

  } else {

    this.div_.style.visibility = "visible";
  }
};

/**
 * Sets the options for the InfoBox. Note that changes to the <tt>maxWidth</tt>,
 *  <tt>closeBoxMargin</tt>, <tt>closeBoxURL</tt>, and <tt>enableEventPropagation</tt>
 *  properties have no affect until the current InfoBox is <tt>close</tt>d and a new one
 *  is <tt>open</tt>ed.
 * @param {InfoBoxOptions} opt_opts
 */
InfoBox.prototype.setOptions = function (opt_opts) {
  if (typeof opt_opts.boxClass !== "undefined") { // Must be first

    this.boxClass_ = opt_opts.boxClass;
    this.setBoxStyle_();
  }
  if (typeof opt_opts.boxStyle !== "undefined") { // Must be second

    this.boxStyle_ = opt_opts.boxStyle;
    this.setBoxStyle_();
  }
  if (typeof opt_opts.content !== "undefined") {

    this.setContent(opt_opts.content);
  }
  if (typeof opt_opts.disableAutoPan !== "undefined") {

    this.disableAutoPan_ = opt_opts.disableAutoPan;
  }
  if (typeof opt_opts.maxWidth !== "undefined") {

    this.maxWidth_ = opt_opts.maxWidth;
  }
  if (typeof opt_opts.pixelOffset !== "undefined") {

    this.pixelOffset_ = opt_opts.pixelOffset;
  }
  if (typeof opt_opts.alignBottom !== "undefined") {

    this.alignBottom_ = opt_opts.alignBottom;
  }
  if (typeof opt_opts.position !== "undefined") {

    this.setPosition(opt_opts.position);
  }
  if (typeof opt_opts.zIndex !== "undefined") {

    this.setZIndex(opt_opts.zIndex);
  }
  if (typeof opt_opts.closeBoxMargin !== "undefined") {

    this.closeBoxMargin_ = opt_opts.closeBoxMargin;
  }
  if (typeof opt_opts.closeBoxURL !== "undefined") {

    this.closeBoxURL_ = opt_opts.closeBoxURL;
  }
  if (typeof opt_opts.infoBoxClearance !== "undefined") {

    this.infoBoxClearance_ = opt_opts.infoBoxClearance;
  }
  if (typeof opt_opts.isHidden !== "undefined") {

    this.isHidden_ = opt_opts.isHidden;
  }
  if (typeof opt_opts.visible !== "undefined") {

    this.isHidden_ = !opt_opts.visible;
  }
  if (typeof opt_opts.enableEventPropagation !== "undefined") {

    this.enableEventPropagation_ = opt_opts.enableEventPropagation;
  }

  if (this.div_) {

    this.draw();
  }
};

/**
 * Sets the content of the InfoBox.
 *  The content can be plain text or an HTML DOM node.
 * @param {string|Node} content
 */
InfoBox.prototype.setContent = function (content) {
  this.content_ = content;

  if (this.div_) {

    if (this.closeListener_) {

      google.maps.event.removeListener(this.closeListener_);
      this.closeListener_ = null;
    }

    // Odd code required to make things work with MSIE.
    //
    if (!this.fixedWidthSet_) {

      this.div_.style.width = "";
    }

    if (typeof content.nodeType === "undefined") {
      this.div_.innerHTML = this.getCloseBoxImg_() + content;
    } else {
      this.div_.innerHTML = this.getCloseBoxImg_();
      this.div_.appendChild(content);
    }

    // Perverse code required to make things work with MSIE.
    // (Ensures the close box does, in fact, float to the right.)
    //
    if (!this.fixedWidthSet_) {
      this.div_.style.width = this.div_.offsetWidth + "px";
      if (typeof content.nodeType === "undefined") {
        this.div_.innerHTML = this.getCloseBoxImg_() + content;
      } else {
        this.div_.innerHTML = this.getCloseBoxImg_();
        this.div_.appendChild(content);
      }
    }

    this.addClickHandler_();
  }

  /**
   * This event is fired when the content of the InfoBox changes.
   * @name InfoBox#content_changed
   * @event
   */
  google.maps.event.trigger(this, "content_changed");
};

/**
 * Sets the geographic location of the InfoBox.
 * @param {LatLng} latlng
 */
InfoBox.prototype.setPosition = function (latlng) {

  this.position_ = latlng;

  if (this.div_) {

    this.draw();
  }

  /**
   * This event is fired when the position of the InfoBox changes.
   * @name InfoBox#position_changed
   * @event
   */
  google.maps.event.trigger(this, "position_changed");
};

/**
 * Sets the zIndex style for the InfoBox.
 * @param {number} index
 */
InfoBox.prototype.setZIndex = function (index) {

  this.zIndex_ = index;

  if (this.div_) {

    this.div_.style.zIndex = index;
  }

  /**
   * This event is fired when the zIndex of the InfoBox changes.
   * @name InfoBox#zindex_changed
   * @event
   */
  google.maps.event.trigger(this, "zindex_changed");
};

/**
 * Sets the visibility of the InfoBox.
 * @param {boolean} isVisible
 */
InfoBox.prototype.setVisible = function (isVisible) {

  this.isHidden_ = !isVisible;
  if (this.div_) {
    this.div_.style.visibility = (this.isHidden_ ? "hidden" : "visible");
  }
};

/**
 * Returns the content of the InfoBox.
 * @returns {string}
 */
InfoBox.prototype.getContent = function () {

  return this.content_;
};

/**
 * Returns the geographic location of the InfoBox.
 * @returns {LatLng}
 */
InfoBox.prototype.getPosition = function () {

  return this.position_;
};

/**
 * Returns the zIndex for the InfoBox.
 * @returns {number}
 */
InfoBox.prototype.getZIndex = function () {

  return this.zIndex_;
};

/**
 * Returns a flag indicating whether the InfoBox is visible.
 * @returns {boolean}
 */
InfoBox.prototype.getVisible = function () {

  var isVisible;

  if ((typeof this.getMap() === "undefined") || (this.getMap() === null)) {
    isVisible = false;
  } else {
    isVisible = !this.isHidden_;
  }
  return isVisible;
};

/**
 * Shows the InfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
 */
InfoBox.prototype.show = function () {

  this.isHidden_ = false;
  if (this.div_) {
    this.div_.style.visibility = "visible";
  }
};

/**
 * Hides the InfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
 */
InfoBox.prototype.hide = function () {

  this.isHidden_ = true;
  if (this.div_) {
    this.div_.style.visibility = "hidden";
  }
};

/**
 * Adds the InfoBox to the specified map or Street View panorama. If <tt>anchor</tt>
 *  (usually a <tt>google.maps.Marker</tt>) is specified, the position
 *  of the InfoBox is set to the position of the <tt>anchor</tt>. If the
 *  anchor is dragged to a new location, the InfoBox moves as well.
 * @param {Map|StreetViewPanorama} map
 * @param {MVCObject} [anchor]
 */
InfoBox.prototype.open = function (map, anchor) {

  var me = this;

  if (anchor) {

    this.position_ = anchor.getPosition();
    this.moveListener_ = google.maps.event.addListener(anchor, "position_changed", function () {
      me.setPosition(this.getPosition());
    });
  }

  this.setMap(map);

  if (this.div_) {

    this.panBox_();
  }
};

/**
 * Removes the InfoBox from the map.
 */
InfoBox.prototype.close = function () {

  var i;

  if (this.closeListener_) {

    google.maps.event.removeListener(this.closeListener_);
    this.closeListener_ = null;
  }

  if (this.eventListeners_) {
    
    for (i = 0; i < this.eventListeners_.length; i++) {

      google.maps.event.removeListener(this.eventListeners_[i]);
    }
    this.eventListeners_ = null;
  }

  if (this.moveListener_) {

    google.maps.event.removeListener(this.moveListener_);
    this.moveListener_ = null;
  }

  if (this.contextListener_) {

    google.maps.event.removeListener(this.contextListener_);
    this.contextListener_ = null;
  }

  this.setMap(null);
};
