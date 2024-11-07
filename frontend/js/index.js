$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');
    const pie_chart_data = [];
    const period = 7;
    let request=0;

    //Ajoute le pseudo de l'utilisateur courant
    $.ajax({
        url: `${prefix}/backend/auth.php/self`,
        method: 'GET',
        dataType: 'json',
        success: function(login_data) {
            $.ajax({
                url: `${prefix}/backend/users.php/login/${login_data.login}`,
                method: 'GET',
                dataType: 'json',
                success: function(username_data) {
                    $('#username-holder').text(username_data[0].pseudo);
                }
            });
        }
    });

    //Rempli les types d'ailments
    $.ajax({
        url: `${prefix}/backend/repas.php/self`,
        method: 'GET',
        dataType: 'json',
        success: function(repas_data) {
            if (Array.isArray(repas_data)) {
                const currentDate = new Date();
    
                const cutoffDate = new Date(currentDate.setDate(currentDate.getDate() - period));
    
                const filteredRepasData = repas_data.filter(repas => {
                    const repasDate = new Date(repas.date_heure);
                    return repasDate >= cutoffDate;
                });

                let request = 0;
                filteredRepasData.forEach(repas => {
                    $.ajax({
                        url: `${prefix}/backend/aliments.php/${repas.id_aliment}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(aliment_data) {
                            const existing_data = pie_chart_data.find(d => d.name === aliment_data.type_aliment);
    
                            if (existing_data) {
                                existing_data.value += parseFloat(repas.quantite);
                            } else {
                                pie_chart_data.push({ name: aliment_data.type_aliment, value: parseFloat(repas.quantite) });
                            }
    
                            request++;
    
                            if (request === filteredRepasData.length) {
                                $('#aliment-type-chart').append(createPieChart(pie_chart_data));
                            }
                        },
                    });
                });
            }
        },
    });

});

const columns = ["name", "value"];

function createPieChart(data) {
    // Specify the chart’s dimensions.
    const width = 928;
    const height = Math.min(width, 500);
  
    // Create the color scale.
    const color = d3.scaleOrdinal()
        .domain(data.map(d => d.name))
        .range(d3.quantize(t => d3.interpolateSpectral(t * 0.8 + 0.1), data.length).reverse());
  
    // Create the pie layout and arc generator.
    const pie = d3.pie()
        .sort(null)
        .value(d => d.value);
  
    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(Math.min(width, height) / 2 - 1);
  
    const labelRadius = arc.outerRadius()() * 0.8;
  
    // A separate arc generator for labels.
    const arcLabel = d3.arc()
        .innerRadius(labelRadius)
        .outerRadius(labelRadius);
  
    const arcs = pie(data);
  
    // Create the SVG container.
    const svg = d3.create("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("viewBox", [-width / 2, -height / 2, width, height])
        .attr("style", "max-width: 100%; height: auto; font: 10px sans-serif;");
  
    // Add a sector path for each value.
    svg.append("g")
        .attr("stroke", "white")
      .selectAll("path")
      .data(arcs)
      .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("d", arc)
      .append("title")
        .text(d => `${d.data.name}: ${d.data.value.toLocaleString("en-US")}`);
  
    // Add labels to each arc, if the value is not 0 and there is enough space.
    svg.append("g")
        .attr("text-anchor", "middle")
      .selectAll("text")
      .data(arcs.filter(d => d.data.value > 0))
      .join("text")
        .attr("transform", d => `translate(${arcLabel.centroid(d)})`)
        .call(text => text.append("tspan")
            .attr("y", "-0.4em")
            .attr("font-weight", "bold")
            .attr("font-size", "150%")
            .text(d => d.data.name))
        .call(text => text.filter(d => (d.endAngle - d.startAngle) > 0.25).append("tspan")
            .attr("x", 0)
            .attr("y", "0.7em")
            .attr("fill-opacity", 0.7)
            .attr("font-size", "150%")
            .text(d => d.data.value.toLocaleString("en-US") + ' g'));
  
    return svg.node();
}  