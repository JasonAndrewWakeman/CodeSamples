package Multi_Threaded_Scala;

import java.io.IOException;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Scanner;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

/**
 * A simple example of a server. This server accepts connections on port 2222,
 * reads a key from the input stream, and returns the FakeDatabase record
 * matching that key.
 */
public class SimpleServer {
  public static void main(String[] args) {
    new SimpleServer().runServer(2222);

  }

  /**
   * Basic server loop.
   * 
   * @param port
   *            the port number on which to listen
   */
  public void runServer(int port) {
    while (true) {
      ServerSocket ss = null;
      try {
        ss = new ServerSocket(port);
        while (true) {
          System.out.println("\nServer listening on " + port);

          // blocks here until a client attempts to connect

          final Socket s = ss.accept();

          // creates a new thread to handle the connection
          Thread serverThread = new Thread("server thread") {
            public void run() {
              try {
                handleConnection(s);
              } catch (IOException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
              }

            }

          };
          // starts a separate thread which performs the slow response
          serverThread.start();

          // mutual exclusion to wait until the dbThread finishes
          // synchronized (dbThread) {
          // try {
          // dbThread.wait();
          // } catch (InterruptedException e) {
          // e.printStackTrace();
          // }
          // returns the value which should now be in the cache at
          // index
          // key
          // value = getLocalValue(key);
        }// ends mutex

      } catch (IOException e) {
        System.out.println("I/O error: " + e);
      } finally {
        if (ss != null) {
          try {
            ss.close();
          } catch (IOException e) {
            // error trying to close the socket, not much we can do
            System.out.println("Error closing socket " + e);
          }
        }
      }
    }
  }

  /**
   * Helper method for handling a client connection. Closes the socket (and
   * therefore the associated streams) when the method returns.
   * 
   * @param s
   *            Socket representing the client connection
   * @throws IOException
   */
  private void handleConnection(Socket s) throws IOException {
    try {
      // We expect line-oriented text input, so wrap the input stream
      // in a Scanner
      Scanner scanner = new Scanner(s.getInputStream());
      String text = scanner.nextLine();
      String result;
      try {
        int key = Integer.parseInt(text.trim());
        result = FakeDatabase.retrieve(key);
      } catch (NumberFormatException e) {
        result = "INVALID QUERY";
      } catch (NoSuchEntryException e) {
        result = "NO MATCH";
      }

      // Now write a response to the client
      PrintWriter pw = new PrintWriter(s.getOutputStream());
      pw.println(result);

      // always flush the stream
      pw.flush();
    } finally {
      // close the connection in a finally block
      s.close();
    }
  }
}
