# 314-Project
## Downloading
To start, download git from [https://git-scm.com/download/win](https://git-scm.com/download/win).

Once downloaded and installed, create a folder anywhere, then open a Powershell or Command Prompt terminal there.
Run the following commands:

```
git init
git config --global user.email "your@email"
git config --global user.name "Your Username"
git branch -M main
git remote add origin https://github.com/Zahkc/314-Project.git
git pull
```

Always run `git pull` before making any changes to the project, to ensure your local copy is up to date!

You should now have the source downloaded and ready to go.

## Making Changes
To save changes to the project run the following commands in a Powershell or Command Prompt terminal:

```
git add *
git commit -m "Change made"
git push
```

Each commit should always correspond to only a single feature added, changed, or removed from the project. Commit messages should also always be in present tense, e.g., "delete files", "add Account class", "fix unit tests", etc.

## Starting the Project (Windows)
Starting the project is simply. Simply run double click `start.bat` in the `webserver` folder, then navigate to http://localhost:80.

To stop the server when it is running, press `Ctrl + C` in the prompt that opens when `start.bat` is run, followed by `Y` when prompted.

Please note that this program has only been tested on Windows systems and in Google Chrome. The included PHP server
works only on Windows, so if you are running a different operating system you will need to set up your own PHP server.

## Starting the Project (MacOS, Linux)
If not running Windows, follow the below steps to set up a XAMPP server on your local system:

1. Download and install the [XAMPP control panel](https://www.apachefriends.org/download.html) for your operating system.
2. Open the Xampp control panel.
3. Click "Config" for the Apache service.
4. Select "httpd.conf". This will open the `httpd.conf` file in a text editor.
5. Scroll down to the line `Document Root "path/to/xampp/htdocs"`.
6. Change this line to `Document Root "path/to/repository/webserver/www"`.
7. Change the following line to `<Directory "path/to/repository/webserver/www">`.
8. This will ensure that the project's source files are used by the Apache server.
9. Start the Apache service and navigate to `http://localhost`.

## Disclaimer
This project was not completely completed by myself, however I was responsible for roughly 80% of the PHP, all of the JavaScript, and the implementation of the front-end designs (but not the designs themselves). This is a re-upload due to the original repository being deleted. Commit history is unavailable for this same reason. This repository exists to provide evidence of my experience with various front- and back-end languages.
