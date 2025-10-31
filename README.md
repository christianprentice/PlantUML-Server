# 🖼️ PlantUML-Server

Service to remotely run **PlantUML** through **Java**. This project enables you to generate diagrams (like sequence, class, and activity diagrams) on a server by providing the PlantUML definition code via an HTTP request.

---

## 🛠️ Dependencies and Prerequisites

To run this server, you must have the following dependencies installed and accessible in your system's PATH:

* **`openjdk`**: The core PlantUML library is a Java application and requires an OpenJDK runtime environment.
* **`composer`**: The PHP dependency manager.
* **`pnpm`**: The JavaScript package manager (likely for handling frontend assets or utilities).

---

## 🚀 Installation and Setup

Follow these steps to clone the repository and install the necessary dependencies:

### 1. Clone the Repository

Navigate to your desired directory and clone the project:

```bash
git clone [https://github.com/christianprentice/PlantUML-Server.git](https://github.com/christianprentice/PlantUML-Server.git)
cd PlantUML-Server
```

### 2. Install Dependencies

The project uses both PHP and JavaScript dependencies.

**PHP Dependencies (using Composer):**
```bash
composer install
```

**JavaScript Dependencies (using pnpm):**
```bash
pnpm install
```

### 3. Verify PlantUML Setup

Ensure that the PlantUML Java executable (`plantuml.jar`) is correctly placed (typically within the **`jarscript`** directory) and that your system can execute it via **`openjdk`**. The file `envokePlantUML.php` is responsible for handling this execution.

---

## 💻 Usage

The server is designed to be interacted with via HTTP requests, typically **POST** requests, to send PlantUML definition code and receive the rendered image. The primary entry point will be the **`index.php`** file.

### Basic Request Flow

1.  A client sends a **POST** request to your server's endpoint (e.g., `http://your-server/index.php`).
2.  The request body contains the raw PlantUML definition code.
3.  The **`index.php`** script processes the request and calls **`envokePlantUML.php`**.
4.  **`envokePlantUML.php`** uses `openjdk` to execute the PlantUML `.jar` file, passing the code as input.
5.  The generated image (e.g., PNG or SVG) is then returned as the response to the client.

**Note:** You will need to inspect the contents of `index.php` and `envokePlantUML.php` to determine the exact expected **JSON/form data format** for the input PlantUML code.

***

## 🤝 Contributing

Contributions are welcome! If you have suggestions or bug
