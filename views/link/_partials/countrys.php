<?php
/** @var string $data */
?>
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>

<!-- HTML -->
<div class="row mt-5 mb-2">
    <div class="col-12">
        <div class="card bg-light mb-3">
            <div class="card-header border-light p-4">
                <h4 class="mb-3 text-black"><?= Yii::t('app', 'Por país') ?></h4>
                <p class="font-weight-normal mb-0">
                    <?= Yii::t('app', 'Número de visitas por país') ?>
                </p>
            </div>
            <div class="card-body text-center p-0">
                <div id="countrys-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart code -->
<script>
    am4core.ready(function () {

        const data = <?= $data ?>
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create map instance
        const chart = am4core.create("countrys-chart", am4maps.MapChart);
        const interfaceColors = new am4core.InterfaceColorSet();

        try {
            chart.geodata = am4geodata_worldLow;
        } catch (e) {
            chart.raiseCriticalError(new Error("Map geodata could not be loaded. Please download the latest <a href=\"https://www.amcharts.com/download/download-v4/\">amcharts geodata</a> and extract its contents into the same directory as your amCharts files."));
        }

        // Set projection
        chart.projection = new am4maps.projections.Orthographic();
        chart.panBehavior = "rotateLongLat";
        chart.padding(20, 20, 20, 20);

        // Add zoom control
        chart.zoomControl = new am4maps.ZoomControl();

        const homeButton = new am4core.Button();
        homeButton.events.on("hit", function () {
            chart.goHome();
        });

        homeButton.icon = new am4core.Sprite();
        homeButton.padding(7, 5, 7, 5);
        homeButton.width = 30;
        homeButton.icon.path = "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8";
        homeButton.marginBottom = 10;
        homeButton.parent = chart.zoomControl;
        homeButton.insertBefore(chart.zoomControl.plusButton);

        chart.backgroundSeries.mapPolygons.template.polygon.fill = am4core.color("#a3ddf5");
        chart.backgroundSeries.mapPolygons.template.polygon.fillOpacity = 1;
        chart.deltaLongitude = 20;
        chart.deltaLatitude = -20;

        // limits vertical rotation
        chart.adapter.add("deltaLatitude", function (delatLatitude) {
            return am4core.math.fitToRange(delatLatitude, -90, 90);
        })

        // Create map polygon series
        const shadowPolygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
        shadowPolygonSeries.geodata = am4geodata_continentsLow;

        try {
            shadowPolygonSeries.geodata = am4geodata_continentsLow;
        } catch (e) {
            shadowPolygonSeries.raiseCriticalError(new Error("Map geodata could not be loaded. Please download the latest <a href=\"https://www.amcharts.com/download/download-v4/\">amcharts geodata</a> and extract its contents into the same directory as your amCharts files."));
        }

        shadowPolygonSeries.useGeodata = true;
        shadowPolygonSeries.dx = 2;
        shadowPolygonSeries.dy = 2;
        shadowPolygonSeries.mapPolygons.template.fill = am4core.color("#000");
        shadowPolygonSeries.mapPolygons.template.fillOpacity = 0.2;
        shadowPolygonSeries.mapPolygons.template.strokeOpacity = 0;
        shadowPolygonSeries.fillOpacity = 0.1;
        shadowPolygonSeries.fill = am4core.color("#000");

        // Create map polygon series
        const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
        polygonSeries.useGeodata = true;

        polygonSeries.calculateVisualCenter = true;
        polygonSeries.tooltip.background.fillOpacity = 0.2;
        polygonSeries.tooltip.background.cornerRadius = 20;

        const template = polygonSeries.mapPolygons.template;
        template.nonScalingStroke = true;
        template.fill = am4core.color("#f9e3ce");
        template.stroke = am4core.color("#e2c9b0");

        polygonSeries.calculateVisualCenter = true;
        template.propertyFields.id = "id";
        template.tooltipPosition = "fixed";
        template.fillOpacity = 1;

        template.events.on("over", function (event) {
            if (event.target.dummyData) {
                event.target.dummyData.isHover = true;
            }
        })
        template.events.on("out", function (event) {
            if (event.target.dummyData) {
                event.target.dummyData.isHover = false;
            }
        })

        const hs = polygonSeries.mapPolygons.template.states.create("hover");
        hs.properties.fillOpacity = 1;
        hs.properties.fill = am4core.color("#4a5073");


        const graticuleSeries = chart.series.push(new am4maps.GraticuleSeries());
        graticuleSeries.mapLines.template.stroke = am4core.color("#fff");
        graticuleSeries.fitExtent = false;
        graticuleSeries.mapLines.template.strokeOpacity = 0.2;
        graticuleSeries.mapLines.template.stroke = am4core.color("#fff");


        const measelsSeries = chart.series.push(new am4maps.MapPolygonSeries());
        measelsSeries.tooltip.background.fillOpacity = 0;
        measelsSeries.tooltip.background.cornerRadius = 20;
        measelsSeries.tooltip.autoTextColor = false;
        measelsSeries.tooltip.label.fill = am4core.color("#000");
        measelsSeries.tooltip.dy = -5;

        const measelTemplate = measelsSeries.mapPolygons.template;
        measelTemplate.fill = am4core.color("#3fb7eb");
        measelTemplate.strokeOpacity = 0;
        measelTemplate.fillOpacity = 0.75;
        measelTemplate.tooltipPosition = "fixed";

        const hs2 = measelsSeries.mapPolygons.template.states.create("hover");
        hs2.properties.fillOpacity = 1;
        hs2.properties.fill = am4core.color("#3fb7eb");

        polygonSeries.events.on("inited", function () {
            polygonSeries.mapPolygons.each(function (mapPolygon) {
                const count = data[mapPolygon.id];

                if (count > 0) {
                    const polygon = measelsSeries.mapPolygons.create();
                    polygon.multiPolygon = am4maps.getCircle(mapPolygon.visualLongitude, mapPolygon.visualLatitude, Math.max(0.2, Math.log(count) * Math.LN10 / 10));
                    polygon.tooltipText = mapPolygon.dataItem.dataContext.name + ": " + count;
                    mapPolygon.dummyData = polygon;
                    polygon.events.on("over", function () {
                        mapPolygon.isHover = true;
                    })
                    polygon.events.on("out", function () {
                        mapPolygon.isHover = false;
                    })
                } else {
                    mapPolygon.tooltipText = mapPolygon.dataItem.dataContext.name + ": no data";
                    mapPolygon.fillOpacity = 0.9;
                }

            })
        })


    }); // end am4core.ready()
</script>
