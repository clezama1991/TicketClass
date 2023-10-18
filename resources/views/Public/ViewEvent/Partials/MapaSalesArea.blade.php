<style>
    area.hoverable
{
    fill: transparent;
    stroke:gray; /* Replace with none if you like */
    stroke-width: 4;
    cursor: pointer;
}

area.hoverable:hover
{
 stroke:black;   
}



</style>
<div class="row">
    <div class="col-md-12 p-5">






        
<img 
id="hall-seat-plan"  
 src="{{ asset($event->image_map()) }}" 
alt="stage" 
usemap="#map" />

<map name="map" class="seatmap xxx">
    <!-- SEAT A --> 
    <?php                            
        $zonas_disponibles = $tickets->where('is_hidden', false)->pluck('seat_zone')->toArray();                                
    ?>                      
    @foreach($event->sections_map() as $key => $map_section)
    <area 
        data-seat="{{ in_array($map_section->combine,$zonas_disponibles) ? 'sold' : "x".$map_section->combine }}"
        data-seatzone="{{ strtoupper($map_section->combine)}}" 
        class="selected_zone_map hoverable" 
        alt="{{ $map_section->combine }}"
        title="{{ $map_section->combine }}" 
        data-toggle="{{ in_array(strtoupper($map_section->combine),$zonas_disponibles) ? 'modal' : 'sold' }}" 
        data-target="#myModal"
        href="#" 
        shape="{{$map_section->shape}}" 
        coords="{{$map_section->coords}}"
        >
    @endforeach
</map>

<div class="seat-label">
    <ul>
        <li><img src="{{ asset('assets/images/select_seat/available.png') }}" alt="available">
            Available</li>
        <li><img src="{{ asset('assets/images/select_seat/sold.png') }}" alt="sold"> Sold Out</li>
        <li><img src="{{ asset('assets/images/select_seat/selected.png') }}" alt="selected">
            Selected</li>
    </ul>
</div>

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script src="https://www.jqueryscript.net/demo/rwd-image-maps/jquery.rwdImageMaps.min.js"></script>

<script>
         $('img[usemap]').rwdImageMaps();  
 </script>
