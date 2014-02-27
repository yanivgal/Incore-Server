function findCircles(env, whatImage, minRadius, maxRadius, sensitivity)
    resultFileName = 'c:\wamp\www\incore\results\result.txt'
    okFileName = 'c:\wamp\www\incore\results\ok';
    
    if strcmp(whatImage, 'default')
        inputImage = 'c:\wamp\www\incore\inputs\default\image_from_64.jpeg';
    else
        inputImage = 'c:\wamp\www\incore\inputs\image_from_64.jpeg';
    end

    % Read the input image
    img = imread(inputImage);

    % Find all the dark circles in the image within the radius range.
    [centersDark, radiiDark, metricDark] = ...
        imfindcircles(img, [minRadius maxRadius], ...
        'ObjectPolarity', 'dark', ...
        'Sensitivity', sensitivity);

    % Find all the bright circles in the image within the radius range.
    [centersBright, radiiBright, metricBright] = ...
        imfindcircles(img, [minRadius maxRadius], ...
        'ObjectPolarity', 'bright', ...
        'Sensitivity', sensitivity);

    
    % Print circles data as JSON to file
    fid = fopen(resultFileName, 'wt');
    fprintf(fid, '{"circles":[');
    circlesToFile(fid, centersDark, radiiDark, metricDark, 'dark');
    if ~isempty(radiiDark) && ~isempty(radiiBright)
       fprintf(fid, ',');
    end
    circlesToFile(fid, centersBright, radiiBright, metricBright, 'bright');
    fprintf(fid, ']}');
    fclose(fid);
    
    if strcmp(env, 'dev')
        red = uint8([255 0 0]);
        shapeInserter = vision.ShapeInserter('Shape','Circles', ...
            'BorderColor','Custom','CustomBorderColor',red);
        circles = [];
        for i = 1:length(radiiBright)
            circle = int32([centersBright(i, 1), ...
                centersBright(i, 2), radiiBright(i)]);
            circles = [circle; circles];
        end
        
        for i = 1:length(radiiDark)
            circle = int32([centersDark(i, 1), ...
                centersDark(i, 2), radiiDark(i)]);
            circles = [circle; circles];
        end
        
        imgWithCircles = step(shapeInserter, img, circles);
        
        if strcmp(whatImage, 'default')
            imwrite(imgWithCircles, 'c:\wamp\www\incore\results\default.jpeg');
        else
            imwrite(imgWithCircles, 'c:\wamp\www\incore\results\last.jpeg');
        end
    end

    fid = fopen(okFileName, 'wt');
    fclose(fid);
    
    quit;

    imshow(img);
    viscircles(centersDark, radiiDark, 'LineStyle', '--');
    viscircles(centersBright, radiiBright, 'EdgeColor', 'b');
end

