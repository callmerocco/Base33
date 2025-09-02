# Base33 Codec

Base33 is a cross-language encoding scheme using the alphabet:  
`0123456789ABCDEFGHJKMNPQRSTUVWXYZ`  
It omits ambiguous characters (`I`, `L`, `O`) for human-friendly output.

## Features

- Consistent Base33 encoding/decoding in **Go**, **JavaScript**, **Python**, and **PHP**
- Handles leading zero bytes
- Canonicalizes ambiguous input characters (`i`, `l`, `o`, etc.)
- Includes test vectors for interoperability

## Directory Structure

```
packages/
  go/        # Go implementation
  js/        # JavaScript implementation
  php/       # PHP implementation
  python/    # Python implementation
tests/       # Language-specific test runners and vectors
```

## Usage

### Go

```go
import "base33.dev/codec/base33"

encoded := base33.Encode([]byte("hello"))
decoded, err := base33.Decode(encoded)
```

See [packages/go/base33.go](packages/go/base33.go).

---

### JavaScript (Node.js)

```js
import { encode, decode } from './packages/js/base33.js';

const encoded = encode(Buffer.from('hello'));
const decoded = decode(encoded); // returns Buffer
```

See [packages/js/base33.js](packages/js/base33.js).

---

### Python

```py
from base33 import encode, decode

encoded = encode(b'hello')
decoded = decode(encoded)
```

See [packages/python/base33.py](packages/python/base33.py).

---

### PHP

```php
use Base33\Base33;

$encoded = Base33::encode("hello");
$decoded = Base33::decode($encoded);
```

See [packages/php/Base33.php](packages/php/Base33.php).

---

## Testing

Test vectors are provided in [tests/vectors.json](tests/vectors.json).  
Run language-specific tests:

- **Python:**  
  `python3 tests/python_vectors.py`
- **Node.js:**  
  `node tests/js_vectors.js`
- **PHP:**  
  `php tests/php_vectors.php`
- **Go:**  
  `go test ./...` (add your own Go tests if needed)

All tests check encoding/decoding against the shared vectors.

## Canonicalization

Decoders accept ambiguous characters (`i`, `l`, `o`, etc.) and convert them to their canonical forms (`1`, `0`).

## License

MIT License (c) 2025 Base33

---

See [SPEC.md](SPEC.md) for the alphabet and encoding
