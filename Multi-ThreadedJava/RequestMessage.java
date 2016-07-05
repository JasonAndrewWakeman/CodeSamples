package hw3_sample_code;

/**
 * Message type for a request from client.
 */
public class RequestMessage extends AbstractMessage
{
  protected final int key;
  
  public RequestMessage(Component sender, int key)
  {
    super(sender);
    this.key = key;
  }
  
  public int getKey()
  {
    return key;
  }
  
  @Override
  public void dispatch(Component receiver)
  {
    receiver.handle(this);
  }
}
