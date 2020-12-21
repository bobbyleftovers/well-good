<?php
/* Template Name: Post container */

// @WORK TEMPORARY DEMO
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
      body {
        margin: 0;
        padding: 0;
      }

      .post:after {
        content: "ad";
        background-color: lightgrey;
        width: 100%;
        height: 400px;
        display: flex;
        margin-top: 100px;
        justify-content: center;
        align-items: center;
      }

      .container {
        margin-top: 100px;
        display: grid;
        grid-template-columns: 1fr minmax(auto, 854px) 325px 1fr;
        grid-template-rows: auto auto;
        grid-gap: 25px;
        grid-template-areas: 
          " . title . ."
          "social text ad .";
      }

      .container--header {
        margin-top: 100px;
        display: grid;
        grid-template-columns: 1fr minmax(auto, 854px) 325px 1fr;
        grid-template-rows: auto auto auto;
        grid-gap: 25px;
        grid-template-areas: 
          " . title . ."
          ". header ad ."
          "social text ad .";
      }

      .container--video {
        grid-template-rows: auto auto auto;
        grid-template-areas:
          " . title title ."
          " . video video ."
          "social text ad .";
      }
      .container--video.container--header {
        grid-template-rows: auto auto auto auto;
        grid-template-areas:
          " . title title ."
          " . video video ."
          ". header ad ."
          "social text ad .";
      }

      .title {
        grid-area: title;
        font-size: 50px;
      }
      .container--video .title {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
      }

      .video {
        grid-area: video;
        width: 100%;
        height: 600px;
        background-color: purple;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .video:after {
        content: "video";
      }

      .header {
        grid-area: header;
      }
      .header div:nth-child(2) {
        margin-top: 20px;
      }

      .text {
        grid-area: text;
      }

      .social {
        grid-area: social;
        position: -webkit-sticky;
        position: -moz-sticky;
        position: -ms-sticky;
        position: -o-sticky;
        position: sticky;
        top: 0;
        height: 150px; 
        width: 40px; 
        margin-top: -25px;
        margin-bottom: 25px;
        display: flex;
      }
      .social:after {
        content: "";
        width: 100%;
        height: 100%;
        background-color: red;
        margin-top: 25px;
      }

      .ad {
        grid-area: ad;
        position: -webkit-sticky;
        position: -moz-sticky;
        position: -ms-sticky;
        position: -o-sticky;
        position: sticky;
        top: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
        margin-top: -25px;
        margin-bottom: 25px;
      }
      .ad:after {
        content: "ad";
        background-color: green;
        width: 100%;
        height: 100%;
        margin-top: 50px;
      }
      .ad--short {
        height: 300px;
      }
      .ad--medium {
        height: 450px;
      }
      .ad--tall {
        height: 700px;
      }

      .franchise {
        background-color: orange;
        height: 250px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .franchise:after {
        content: "franchise";
      }

      .hero-image {
        background-color: blue; 
        height: 550px; 
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      .hero-image:after {
        content: "hero image";
      }

			@media (max-width: 1281px) { /* $l variable */
      }
			@media (max-width: 999px) { /* $m variable */
        .container {
          margin-top: 100px;
          display: flex;
          flex-direction: column;
        }

        .social {
          display: none;
        }

        .ad {
          position: fixed;
          bottom: 0;
          top: auto;
          height: 100px;
          width: 100%;
          left: 0;
          right: 0;
        }

        .title {
          margin: 0 20px;
        }
        .header {
          margin: 20px 20px 0;
        }
        .header div:nth-child(2) {
          margin-top: 20px;
        }
        .text {
          margin: 20px 20px 0;
        }
        .video {
          margin-top: 20px;
        }
			}

    </style>
  </head>
  <body>
    <div class="post">
      <div class="container container--header">
        <div class="title">This is a post</div>
        <aside class="social"></aside>
        <div class="header">
          <div class="hero-image"></div>
        </div>
        <div class="text">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet. 

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   
        </div>
        <div class="ad ad--tall"></div>
      </div>
    </div>
    <div class="post">
      <div class="container">
        <div class="title">This is a post with no image</div>
        <aside class="social"></aside>
        <div class="text">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet. 

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   
        </div>
        <div class="ad ad--medium"></div>
      </div>
    </div>
    <div class="post">
      <div class="container container--header">
        <div class="title">This is a post with a long enough title to demonstrate the size of the container that contains this title of a post with a long title</div>
        <aside class="social"></aside>
        <div class="header">
          <div class="hero-image"></div>
          <div class="franchise"></div>
        </div>
        <div class="text">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet. 

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   
        </div>
        <div class="ad ad--short"></div>
      </div>
    </div>
    <div class="post">
      <div class="container container--video">
        <div class="title">Video post</div>
        <div class="video"></div>
        <aside class="social"></aside>
        <div class="text">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet. 

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   
        </div>
        <div class="ad ad--tall"></div>
      </div>
    </div>
    <div class="post">
      <div class="container container--video container--header">
        <div class="title">Video post with a franchise and another title long enough to display the width of the container</div>
        <div class="video"></div>
        <aside class="social"></aside>
        <div class="header">
          <div class="franchise"></div>
        </div>
        <div class="text">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet. 

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   

          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non turpis in erat tristique varius. Cras tincidunt nisi nec nisl vulputate, et rutrum turpis congue. Aenean finibus sagittis dolor, eget posuere metus pellentesque id. Pellentesque commodo lobortis metus, suscipit dapibus diam vestibulum vitae. Vestibulum eget leo eleifend, hendrerit orci in, dapibus ligula. Nullam egestas consectetur sem in ornare. Fusce a risus tristique, eleifend est tincidunt, sodales metus. Aenean pellentesque porttitor eros vitae sollicitudin. Phasellus convallis vulputate tincidunt.

          Vestibulum eget aliquam nisi, eget pellentesque velit. Mauris in feugiat justo. Suspendisse potenti. Suspendisse nec est ac eros iaculis aliquam vitae vulputate sapien. Etiam malesuada arcu id leo fermentum, eget convallis justo tristique. Cras eleifend non risus id aliquet. Sed metus orci, volutpat sit amet diam eu, scelerisque varius mauris. Ut feugiat purus id massa feugiat vestibulum. Mauris et nulla neque. Ut dignissim, lectus vel porttitor tristique, libero nisl porttitor felis, sed efficitur felis sapien auctor sapien. Sed pretium lacus eget sollicitudin accumsan.

          Donec a ex at sem auctor vehicula. Sed malesuada, tortor at faucibus interdum, libero nisl varius nisl, eu fermentum arcu neque eu est. Curabitur posuere fermentum ipsum, quis ornare nisl sodales at. Vestibulum mollis dolor nisl, sed mattis lacus imperdiet quis. Proin pellentesque ipsum eget ligula porttitor, et commodo diam pellentesque. Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam varius tincidunt purus. Morbi id posuere quam. Proin ac metus ut risus interdum scelerisque. Donec consectetur nulla massa, quis suscipit enim finibus ac. Sed vitae velit non velit luctus euismod. Nunc aliquam nisl massa, at malesuada justo pretium convallis. Vestibulum sem metus, vehicula et orci quis, tempor tincidunt arcu. Nulla iaculis, est ut tincidunt suscipit, eros augue laoreet est, vel dapibus felis tellus sit amet mauris.

          Nunc suscipit cursus nisi. Quisque consequat ullamcorper fermentum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur viverra, neque eget condimentum commodo, tortor neque bibendum sapien, a pharetra lorem ligula nec dui. Vivamus convallis enim eget augue faucibus viverra quis at ligula. Fusce laoreet consequat rutrum. Ut molestie eleifend dui. Aenean blandit leo a nulla dignissim hendrerit. Duis consequat, justo in mattis ultricies, turpis dui ultricies mi, at sagittis nisi leo vel felis. Mauris vel vestibulum mi, ut dignissim lacus.

          Ut nibh sem, consectetur at dapibus et, interdum in purus. Phasellus volutpat pulvinar velit eu ultrices. Cras augue enim, placerat ut lectus eget, congue imperdiet justo. Curabitur nec nisi rhoncus, pulvinar nibh ut, dapibus dolor. Nam ac tristique nisi. Aenean vulputate nunc at condimentum egestas. Morbi libero mauris, ullamcorper eget est non, gravida varius neque. Proin et malesuada tortor. Nullam porta urna pretium lacus tempor, vel volutpat dui varius. Vivamus vehicula urna ut purus consequat laoreet.   
        </div>
        <div class="ad ad--short"></div>
      </div>
    </div>
  </body>
</html>