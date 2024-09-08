# BCRA Scraper

## Overview

BCRA Scraper is a data scraping tool designed to extract information from the Banco Central de la República Argentina (BCRA) website. This tool allows users to gather valuable financial data for analysis and research purposes.

## Features

- Scrapes data from the BCRA website.
- Simple and easy to set up.
- Runs a local server for data retrieval.

## Requirements

- PHP installed on your machine.
- Access to a terminal or command line interface.

## Installation

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/yourusername/bcra-scraper.git
   cd bcra-scraper
   ```

2. Ensure that you have PHP installed. You can check this by running:
   ```bash
   php -v
   ```

## Usage

To start the local server, run the following command in your terminal:
```bash
./start.sh
```

This command will start a PHP server on `http://0.0.0.0:5000`, and you can access the scraping functionality through your web browser.

## File Structure

- `reservas_bcra.php`: The main PHP file responsible for scraping data from the BCRA website.
- `start.sh`: A shell script to start the PHP server.
- `.gitignore`: Specifies files and directories that should be ignored by Git.

## Contributing

Contributions are welcome! If you have suggestions for improvements or new features, please fork the repository and submit a pull request.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.

## Acknowledgments

- Thanks to the Banco Central de la República Argentina for providing public data.
- Inspired by various open-source scraping projects.
