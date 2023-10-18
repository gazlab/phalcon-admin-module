<div class="form-group">
    <label for="{{ element.getName() }}" class="col-sm-2 control-label">{{ element.getLabel() }}</label>

    <div class="col-sm-10">
        
        <video id="webCam" autoplay playsinline class="img-responsive img-thumbnail"></video>
        <canvas id="canvas" class="img-responsive img-thumbnail" style="display: none;"></canvas>
        <input type="hidden" id="fotoBase64" name="fotoBase64" />
        <div class="row">
            <div class="col-xs-12">                                        
                <button type="button" onclick="takeAPicture()" id="snap" class="btn btn-lg btn-success"><i class="fa fa-camera" aria-hidden="true"></i></button>                                    
                <button type="button" onclick="undoTakeAPicture()" id="undo" style="display: none;" class="btn btn-lg btn-warning"><i class="fa fa-undo" aria-hidden="true"></i></button>
                
                <button type="button" onclick="flipCamera()" id="flip" class="btn btn-lg"><i class="fa fa-refresh" aria-hidden="true"></i></button>
            </div>
        </div>
    
<hr/>

        {{ shipment.foto is not null ? image(shipment.foto,'class':'img-responsive img-thumbnail') : null }}
        {{ file_field('foto', "accept":"image/*") }}
    </div>
</div>