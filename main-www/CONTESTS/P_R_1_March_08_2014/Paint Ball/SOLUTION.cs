using System;
using System.Linq;

namespace PaintBall
{
    public class Ball
    {
        public double Radius;
        public double SurfaceArea { get { return 4 * Math.PI * Math.Pow(Radius, 2); } }
    }

    public class Point
    {
        public double X;
        public double Y;
    }

    public class Polygon
    {
        public Point[] Points;

        public double SectionArea()
        {
            var j = Points.Length - 1;
            double area = 0;
            for (var i = 0; i < Points.Length; ++i)
            {
                area += (Points[j].X + Points[i].X) * (Points[j].Y - Points[i].Y);
                j = i;
            }
            return Math.Abs(area / 2);
        }

        public Point[] Edge(int i)
        {
            var secondIndex = i == Points.Length - 1 ? 0 : i + 1;
            return new[] { Points[i], Points[secondIndex] };
        }

        public double Area(int start = 0)
        {
            for (var i = start; i < Points.Length; ++i)
            {
                for (var j = i + 2; j < Points.Length; ++j)
                {
                    var intersectionPoint = Intersect(Edge(i), Edge(j));
                    if (intersectionPoint == null) continue;

                    var pointsList = Points.ToList();

                    var firstPolygonOutline = pointsList.GetRange(0, i + 1);
                    firstPolygonOutline.Add(intersectionPoint);

                    var secondPolygonOutline = pointsList.GetRange(i + 1, j - i);
                    secondPolygonOutline.Add(intersectionPoint);

                    var firstSection = new Polygon
                    {
                        Points = firstPolygonOutline.Concat(pointsList.GetRange(j + 1, Points.Length - j - 1)).ToArray()
                    };

                    var secondSection = new Polygon
                    {
                        Points = secondPolygonOutline.ToArray()
                    };

                    return firstSection.Area(i) + secondSection.Area();
                }
            }

            return SectionArea();
        }

        // Will only return a value if the lines intersect between the points specified for each edge
        public static Point Intersect(Point[] edge1, Point[] edge2)
        {
            var x1 = edge1[0].X;
            var x2 = edge1[1].X;
            var x3 = edge2[0].X;
            var x4 = edge2[1].X;

            var y1 = edge1[0].Y;
            var y2 = edge1[1].Y;
            var y3 = edge2[0].Y;
            var y4 = edge2[1].Y;

            var denominator = (x1 - x2) * (y3 - y4) - (y1 - y2) * (x3 - x4);
            if (Math.Abs(denominator) <= Double.Epsilon)
            {
                return null;
            }

            var numerator1 = (x1 * y2 - y1 * x2);
            var numerator2 = (x3 * y4 - y3 * x4);
            var point = new Point
            {
                X = (numerator1 * (x3 - x4) - (x1 - x2) * numerator2) / denominator,
                Y = (numerator1 * (y3 - y4) - (y1 - y2) * numerator2) / denominator
            };

            if ((point.X >= x1 && point.X >= x2) ||
                (point.X <= x1 && point.X <= x2) ||
                (point.Y >= y1 && point.Y >= y2) ||
                (point.Y <= y1 && point.Y <= y2) ||
                (point.X >= x3 && point.X >= x4) ||
                (point.X <= x3 && point.X <= x4) ||
                (point.Y >= y3 && point.Y >= y4) ||
                (point.Y <= y3 && point.Y <= y4))
            {
                return null;
            }

            return point;
        }
    }

    class PaintBall
    {
        static void Main(string[] args)
        {
            var loops = int.Parse(Console.ReadLine());
            for (var i = 0; i < loops; ++i)
            {
                var line = Console.ReadLine().Split(' ');
                var radius = double.Parse(line[0]);
                var polygonPointCount = int.Parse(line[1]);
                var ball = new Ball { Radius = radius };
                var roof = new Polygon { Points = new Point[polygonPointCount] };
                for (var j = 0; j < polygonPointCount; ++j)
                {
                    line = Console.ReadLine().Split(' ');
                    var x = double.Parse(line[0]);
                    var y = double.Parse(line[1]);
                    roof.Points[j] = new Point { X = x, Y = y };
                }

                var roofArea = roof.Area();
                var ballSurfaceArea = ball.SurfaceArea;
                var paintCount = (long)Math.Ceiling(roofArea / ballSurfaceArea);
                if (paintCount < 0)
                {
                    paintCount *= -1;
                }
                Console.WriteLine(paintCount);
            }
        }
    }
}
