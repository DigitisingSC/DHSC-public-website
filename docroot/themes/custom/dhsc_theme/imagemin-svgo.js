import imagemin from 'imagemin';
import imageminSvgo from 'imagemin-svgo';

imagemin(['stories/assets/icons/**/*.svg'], 'assets/icons', {
  use: [
    imageminSvgo({
      plugins: [
        {cleanupIDs: {remove: false}},
        {cleanupNumericValues: {floatPrecision: 2}},
        {removeStyleElement: true},
        {removeTitle: true},
        {removeXMLNS: true},
        {removeXMLProcInst: true}
      ],
      multipass: true
    })
  ]
}).then(function () {
  console.log('SVG-Icons were successfully optimized');
});
