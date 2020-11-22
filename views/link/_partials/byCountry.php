<?php
/** @var string $data */
?>
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>

<div class="row mt-5 mb-2">
    <div class="col-12 mb-3">
        <h4 class="mb-3 text-black"><?= Yii::t('app', 'Por país') ?></h4>
        <p class="font-weight-normal mb-0">
            <?= Yii::t('app', 'Número de visitas por país') ?>
        </p>
        <div id="countries-chart"></div>
    </div>
</div>
<!-- Chart code -->
<script>
    am4core.ready(function () {

        am4core.useTheme(am4themes_animated);

        // Create map instance
        const chart = am4core.create("countries-chart", am4maps.MapChart);
        chart.logo.height = -15000;
        const mapData = [<?= $data ?>];

        // Set map definition
        chart.geodata = am4geodata_worldLow;

        // Set projection
        chart.projection = new am4maps.projections.Miller();

        // Create map polygon series
        const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
        polygonSeries.exclude = ["AQ"];
        polygonSeries.useGeodata = true;
        polygonSeries.nonScalingStroke = true;
        polygonSeries.strokeWidth = 0.5;
        polygonSeries.calculateVisualCenter = true;

        const imageSeries = chart.series.push(new am4maps.MapImageSeries());
        imageSeries.data = mapData;
        imageSeries.dataFields.value = "value";

        const imageTemplate = imageSeries.mapImages.template;
        imageTemplate.nonScaling = true

        const circle = imageTemplate.createChild(am4core.Circle);
        circle.fillOpacity = 0.7;
        circle.propertyFields.fill = "color";
        circle.tooltipText = "{name}: [bold]{value}[/]";

        imageSeries.heatRules.push({
            "target": circle,
            "property": "radius",
            "min": 4,
            "max": 30,
            "dataField": "value"
        })

        imageTemplate.adapter.add("latitude", function (latitude, target) {
            const polygon = polygonSeries.getPolygonById(target.dataItem.dataContext.id);
            if (polygon) {
                return polygon.visualLatitude;
            }
            return latitude;
        })

        imageTemplate.adapter.add("longitude", function (longitude, target) {
            const polygon = polygonSeries.getPolygonById(target.dataItem.dataContext.id);
            if (polygon) {
                return polygon.visualLongitude;
            }
            return longitude;
        })
    });
</script>
