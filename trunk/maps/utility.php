<script type="text/javascript">

function getLat(pos)
    {
        var i = 0;
        while (String(pos).charAt(i) != "(")
        {
            i += 1;
        }
        var retval = "";
        i += 1;
        while (String(pos).charAt(i) != ",")
        {
            retval += String(pos).charAt(i);
            i += 1;
        }
        return retval;
    }

    
    function getLong(pos)
    {
        var i = 0;
        while (String(pos).charAt(i) != "(")
        {
            i += 1;
        }
        var retval = "";
        i += 1;
        while (String(pos).charAt(i) != ",")
        {
            i += 1;
        }
        i += 1;
       
        
        retval = String(pos).substring(i, String(pos).length - 1);
        return retval;
    }

function getPosX(el) 
{
    for (var lx=0;
         el != null;
         lx += el.offsetLeft, el = el.offsetParent);
    return lx;
}

function getPosY(el) {
    for (var ly=0;
         el != null;
         ly += el.offsetTop, el = el.offsetParent);
    return ly;
}

function numberWithCommas(x)
{
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function hasClass(element, className)
{
	var classNameArray = element.className;
	var nextClass = "";
	for (var i = 0; i < classNameArray.length; i++)
	{
		while (i < classNameArray.length && classNameArray[i] != " ")
		{
			nextClass += classNameArray[i];
			i++;
		}

		if (nextClass == className)
			return true;
		
		nextClass = "";
	}

	return false;
}

function ExtractNumber(value)
{
  var n = parseInt(value);
  return n == null || isNaN(n) ? 0 : n;
}


</script>
