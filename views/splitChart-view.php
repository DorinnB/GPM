
<style>
.chart{
max-width: 80%;
margin: 0 10%;
min-height: 400px;
}
.carousel-control {
  max-width: 10%;
}
</style>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div id="chart" class="carousel-inner">

        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Load plotrly librairie (chart) -->
<script src="lib/plotly/plotly-latest.min.js"></script>

<script type="text/javascript" src="js/splitChart.js"></script>
