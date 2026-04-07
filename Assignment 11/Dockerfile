# Configures an Express web server and copies the current folder content
# to use as app root.

# Use the following commands to build and run the server.
#   docker build -t express-server .
#   docker run -d -p 80:80 --name=express-server express-server

# Then open a web browser and connect to http://localhost .

# References:
#   https://code.visualstudio.com/docs/containers/quickstart-node
#   https://docs.docker.com/develop/develop-images/dockerfile_best-practices/

# Use a lightweight Nginx image as the base
FROM nginx:alpine

# Copy the local files into the default Nginx HTML directory
COPY . /usr/share/nginx/html

# The container will run Nginx and expose port 80 by default

EXPOSE 80
CMD ["node", "app.js"]
