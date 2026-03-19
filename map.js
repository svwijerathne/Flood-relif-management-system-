// 1. Function to determine color based on severity
function getColor(risk) {
    const r = risk ? risk.toLowerCase() : 'low';
    if (r === 'high') return '#e74c3c';   
    if (r === 'medium') return '#e67e22'; 
    return '#2ecc71';                    
}

// 2. Fetch District Stats and then load the Map Shapes
fetch('map.php')
    .then(res => res.json())
    .then(stats => {
        
        fetch('geoBoundaries-LKA-ADM2.geojson') 
            .then(res => res.json())
            .then(geoData => {
                L.geoJson(geoData, {
                    style: function (feature) {
                    
                        let dName = feature.properties.shapeName.replace(" District", "").trim();
                        
                        // If district not in DB, default to 'low' (Green)
                        const data = stats[dName] || { severity: 'low' };

                        return {
                            fillColor: getColor(data.severity), 
                            weight: 1,
                            color: 'white',
                            fillOpacity: 0.7
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        layer.on('mouseover', function (e) {
                            let dName = feature.properties.shapeName.replace(" District", "").trim();
                            
                            
                            const data = stats[dName] || { sent: 0, total: 0, severity: 'low' };

                            layer.bindTooltip(`
                                <div style="text-align:center; font-family: sans-serif;">
                                    <b style="font-size:14px;">${dName}</b><br>
                                    <hr style="margin:5px 0;">
                                    <span style="color:${getColor(data.severity)}; font-weight:bold;">
                                        Status: ${data.severity.toUpperCase()}
                                    </span><br>
                                    <b>Delivered: ${data.sent}</b><br>
                                    <small>Total Requests: ${data.total}</small>
                                </div>
                            `).openTooltip();

                            e.target.setStyle({ fillOpacity: 0.9, weight: 3 });
                        });
                        layer.on('mouseout', (e) => e.target.setStyle({ fillOpacity: 0.7, weight: 1 }));
                    }
                }).addTo(map);
            });
    });
