window.onload = function() {
  const ui = SwaggerUIBundle({
    url: "/swagger/openapi.yaml",
    dom_id: '#swagger-ui',
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    layout: "StandaloneLayout"
  })
  window.ui = ui
}
