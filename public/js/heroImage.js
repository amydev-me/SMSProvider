// window.onload = function () { 
// var msg1 = document.getElementsByClassName('heromsg')[0]
// msg1.style.animationName = "example";
// msg1.style.display = "block";
// var msg2 = document.getElementsByClassName('heromsg')[1]
// msg2.style.animationName = "example2";
// setTimeout(function(){
// msg2.style.display = "block";
// msg1.style.display = "none";
//  }, 5000);}
function myFunction() {
  document.getElementsByClassName("heromsg")[0].style.width = "500px";
}
var Carousel = {
  width: 300,     // Images are forced into a width of this many pixels.
  numVisible: 1,  // The number of images visible at once.
  duration: 300,  // Animation duration in milliseconds.
  padding: 5      // Vertical padding around each image, in pixels.
};
function rotateForward() {
  var carousel = Carousel.carousel,
    children = carousel.children,
    firstChild = children[0],
    lastChild = children[children.length - 1];
  carousel.insertBefore(lastChild, firstChild);
}
function rotateBackward() {
  var carousel = Carousel.carousel,
    children = carousel.children,
    firstChild = children[0],
    lastChild = children[children.length - 1];
  carousel.insertBefore(firstChild, lastChild.nextSibling);
}

function animate(begin, end, finalTask) {
  var wrapper = Carousel.wrapper,
    carousel = Carousel.carousel,
    change = end - begin,
    duration = Carousel.duration,
    startTime = Date.now();
  carousel.style.top = begin + 'px';
  var animateInterval = window.setInterval(function () {
    var t = Date.now() - startTime;
    if (t >= duration) {
      window.clearInterval(animateInterval);
      finalTask();
      return;
    }
    t /= (duration / 2);
    var top = begin + (t < 1 ? change / 2 * Math.pow(t, 3) :
      change / 2 * (Math.pow(t - 2, 3) + 2));
    carousel.style.top = top + 'px';
  }, 1000 / 60);
}
function auto ( ) {
  animate(0, -Carousel.rowHeight, function () {
    rotateBackward();
    carousel.style.top = '0';
  });}
function scaleMessage ( ){
  var w = window.innerWidth;
  if (w>= 1025) {
    Carousel.width = 400;
  }
  if(w>= 1331){
    Carousel.width = 500;
  }
  if(w>= 2000){
    Carousel.width = 600;
  }
}
window.onload = function () {
  scaleMessage( );
  document.getElementById('spinner').style.display = 'none';
  var carousel = Carousel.carousel = document.getElementById('carousel'),
    images = carousel.getElementsByTagName('img'),
    numImages = images.length,
    imageWidth = Carousel.width,
    aspectRatio = images[0].width / images[0].height,
    imageHeight = imageWidth / aspectRatio,
    padding = Carousel.padding,
    rowHeight = Carousel.rowHeight = imageHeight + 2 * padding;
  carousel.style.width = imageWidth + 'px';
  setInterval('auto();',5000);
  for (var i = 0; i < numImages; ++i) {
    var image = images[i],
      frame = document.createElement('div');
    frame.className = 'pictureFrame';
    var aspectRatio = image.offsetWidth / image.offsetHeight;
    image.style.width = frame.style.width = imageWidth + 'px';
    image.style.height = imageHeight + 'px';
    image.style.paddingTop = padding + 'px';
    image.style.paddingBottom = padding + 'px';
    frame.style.height = rowHeight + 'px';
    carousel.insertBefore(frame, image);
    frame.appendChild(image);
  }
  Carousel.rowHeight = carousel.getElementsByTagName('div')[0].offsetHeight;
  carousel.style.height = Carousel.numVisible * Carousel.rowHeight + 'px';
  carousel.style.visibility = 'visible';
  var wrapper = Carousel.wrapper = document.createElement('div');
  wrapper.id = 'carouselWrapper';
  wrapper.style.width = carousel.offsetWidth + 'px';
  wrapper.style.height = carousel.offsetHeight + 'px';
  carousel.parentNode.insertBefore(wrapper, carousel);
  wrapper.appendChild(carousel);
  var prevButton = document.getElementById('prev'),
    nextButton = document.getElementById('next');
  // prevButton.onclick = function () {
  //   prevButton.disabled = nextButton.disabled = true;
  //   rotateForward();
  //   animate(-Carousel.rowHeight, 0, function () {
  //     carousel.style.top = '0';
  //     prevButton.disabled = nextButton.disabled = false;
  //   });
  // };
  // nextButton.onclick = function () {
  //   prevButton.disabled = nextButton.disabled = true;
  //   animate(0, -Carousel.rowHeight, function () {
  //     rotateBackward();
  //     carousel.style.top = '0';
  //     prevButton.disabled = nextButton.disabled = false;
  //   });
  // };
};